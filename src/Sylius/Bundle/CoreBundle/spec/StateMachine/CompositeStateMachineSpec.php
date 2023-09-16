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

namespace spec\Sylius\Bundle\CoreBundle\StateMachine;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\CoreBundle\StateMachine\Exception\StateMachineExecutionException;
use Sylius\Bundle\CoreBundle\StateMachine\StateMachineInterface;
use Webmozart\Assert\InvalidArgumentException;

final class CompositeStateMachineSpec extends ObjectBehavior
{
    function it_throws_an_exception_when_no_state_machine_is_passed(): void
    {
        $this->beConstructedWith([]);
        $this
            ->shouldThrow(
                new InvalidArgumentException('At least one state machine adapter should be provided.'),
            )->duringInstantiation()
        ;
    }

    function it_throws_an_exception_when_any_of_passed_objects_is_not_a_state_machine(): void
    {
        $this->beConstructedWith([new \stdClass()]);
        $this
            ->shouldThrow(
                new InvalidArgumentException(
                    sprintf('All state machine adapters should implement the "%s" interface.', StateMachineInterface::class),
                ),
            )->duringInstantiation()
        ;
    }

    function it_does_not_throw_an_exception_when_all_passed_objects_are_state_machines(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
    ): void {
        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->shouldNotThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_invokes_the_can_method_on_a_first_state_machine_with_a_configured_graph_for_a_given_subject(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $stateMachineOne->can($subject, 'graph', 'transition')->willThrow(new StateMachineExecutionException());
        $stateMachineTwo->can($subject, 'graph', 'transition')->willReturn(false);

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->can($subject, 'graph', 'transition')->shouldReturn(false);
    }

    function it_throws_the_last_caught_exception_when_no_state_machine_is_configured_for_a_given_subject_on_a_can_call(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $firstException = new StateMachineExecutionException();
        $secondException = new StateMachineExecutionException();

        $stateMachineOne->can($subject, 'graph', 'transition')->willThrow($firstException);
        $stateMachineTwo->can($subject, 'graph', 'transition')->willThrow($secondException);

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->shouldThrow($secondException)->during('can', [$subject, 'graph', 'transition']);
    }

    function it_invokes_the_apply_method_on_a_first_state_machine_with_a_configured_graph_for_a_given_subject(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $stateMachineOne->apply($subject, 'graph', 'transition', [])->willThrow(new StateMachineExecutionException());
        $stateMachineTwo->apply($subject, 'graph', 'transition', [])->shouldBeCalled();

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->apply($subject, 'graph', 'transition');
    }

    function it_throws_the_last_caught_exception_when_no_state_machine_is_configured_for_a_given_subject_on_an_apply_call(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $firstException = new StateMachineExecutionException();
        $secondException = new StateMachineExecutionException();

        $stateMachineOne->apply($subject, 'graph', 'transition', [])->willThrow($firstException);
        $stateMachineTwo->apply($subject, 'graph', 'transition', [])->willThrow($secondException);

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->shouldThrow($secondException)->during('apply', [$subject, 'graph', 'transition']);
    }

    function it_invokes_the_get_enabled_transition_method_on_a_first_state_machine_with_a_configured_graph_for_a_given_subject(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $stateMachineOne->getEnabledTransition($subject, 'graph')->willThrow(new StateMachineExecutionException());
        $stateMachineTwo->getEnabledTransition($subject, 'graph')->willReturn([]);

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->getEnabledTransition($subject, 'graph')->shouldReturn([]);
    }

    function it_throws_the_last_caught_exception_when_no_state_machine_is_configured_for_a_given_subject_on_a_get_enabled_transition_call(
        StateMachineInterface $stateMachineOne,
        StateMachineInterface $stateMachineTwo,
        \stdClass $subject,
    ): void {
        $firstException = new StateMachineExecutionException();
        $secondException = new StateMachineExecutionException();

        $stateMachineOne->getEnabledTransition($subject, 'graph')->willThrow($firstException);
        $stateMachineTwo->getEnabledTransition($subject, 'graph')->willThrow($secondException);

        $this->beConstructedWith([$stateMachineOne, $stateMachineTwo]);
        $this->shouldThrow($secondException)->during('getEnabledTransition', [$subject, 'graph']);
    }
}
