<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Abstraction\StateMachine;

use SM\Factory\FactoryInterface;
use SM\SMException;
use Sylius\Abstraction\StateMachine\Exception\StateMachineExecutionException;

final class WinzouStateMachineAdapter implements StateMachineInterface
{
    public function __construct(private FactoryInterface $winzouStateMachineFactory)
    {
    }

    public function can(object $subject, string $graphName, string $transition): bool
    {
        try {
            return $this->getStateMachine($subject, $graphName)->can($transition);
        } catch (SMException $exception) {
            throw new StateMachineExecutionException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function apply(object $subject, string $graphName, string $transition, array $context = []): void
    {
        try {
            $this->getStateMachine($subject, $graphName)->apply($transition);
        } catch (SMException $exception) {
            throw new StateMachineExecutionException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getEnabledTransitions(object $subject, string $graphName): array
    {
        try {
            $stateMachine = $this->getStateMachine($subject, $graphName);
            $transitions = $this->getTransitionsConfig($stateMachine);
        } catch (SMException $exception) {
            throw new StateMachineExecutionException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $result = [];

        foreach ($transitions as $transitionName => $transitionConfig) {
            $froms = $transitionConfig['from'];
            $tos = array($transitionConfig['to']);
            $result[] = new Transition($transitionName, $froms, $tos);
        }

        return $result;
    }

    /**
     * @return array<string, array{from: array<string>, to: string}>
     * @throws \ReflectionException
     */
    private function getTransitionsConfig(\SM\StateMachine\StateMachineInterface $stateMachine): array
    {
        $reflection = new \ReflectionClass($stateMachine);
        $configProperty = $reflection->getProperty('config');
        $configProperty->setAccessible(true);
        $configPropertyValue = $configProperty->getValue($stateMachine);

        return $configPropertyValue['transitions'];
    }

    /**
     * @throws StateMachineExecutionException
     */
    public function getTransitionFromState(object $subject, string $graphName, string $fromState): ?string
    {
        foreach ($this->getEnabledTransitions($subject, $graphName) as $transition) {
            if ($transition->getFroms() !== null && in_array($fromState, $transition->getFroms(), true)) {
                return $transition->getName();
            }
        }

        return null;
    }

    /**
     * @throws StateMachineExecutionException
     */
    public function getTransitionToState(object $subject, string $graphName, string $toState): ?string
    {
        foreach ($this->getEnabledTransitions($subject, $graphName) as $transition) {
            if ($transition->getTos() !== null && in_array($toState, $transition->getTos(), true)) {
                return $transition->getName();
            }
        }

        return null;
    }

    private function getStateMachine(object $subject, string $graphName): \SM\StateMachine\StateMachineInterface
    {
        return $this->winzouStateMachineFactory->get($subject, $graphName);
    }
}
