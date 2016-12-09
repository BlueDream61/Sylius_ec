<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Payment\Provider;

use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Exception\UnresolvedDefaultPaymentMethodException;
use Sylius\Component\Payment\Factory\PaymentFactoryInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Payment\Resolver\DefaultPaymentMethodResolverInterface;
use Sylius\Component\Resource\StateMachine\StateMachineInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class OrderPaymentProvider implements OrderPaymentProviderInterface
{
    /**
     * @var DefaultPaymentMethodResolverInterface
     */
    private $defaultPaymentMethodResolver;

    /**
     * @var PaymentFactoryInterface
     */
    private $paymentFactory;

    /**
     * @var StateMachineFactoryInterface
     */
    private $stateMachineFactory;

    /**
     * @var string
     */
    private $targetState;

    /**
     * @param DefaultPaymentMethodResolverInterface $defaultPaymentMethodResolver
     * @param PaymentFactoryInterface $paymentFactory
     * @param StateMachineFactoryInterface $stateMachineFactory
     * @param string $targetState
     */
    public function __construct(
        DefaultPaymentMethodResolverInterface $defaultPaymentMethodResolver,
        PaymentFactoryInterface $paymentFactory,
        StateMachineFactoryInterface $stateMachineFactory,
        $targetState = PaymentInterface::STATE_CART
    ) {
        $this->defaultPaymentMethodResolver = $defaultPaymentMethodResolver;
        $this->paymentFactory = $paymentFactory;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->targetState = $targetState;
    }

    /**
     * {@inheritdoc}
     */
    public function provideOrderPayment(OrderInterface $order)
    {
        /** @var $payment PaymentInterface */
        $payment = $this->paymentFactory->createWithAmountAndCurrencyCode($order->getTotal(), $order->getCurrencyCode());

        $paymentMethod = $this->getDefaultPaymentMethod($payment, $order);
        $lastPayment = $this->getLastPayment($order);

        if (null !== $lastPayment) {
            $paymentMethod = $lastPayment->getMethod();
        }

        if (null === $paymentMethod) {
            return null;
        }

        $payment->setMethod($paymentMethod);
        $this->applyRequiredTransition($payment);

        return $payment;
    }

    /**
     * @param OrderInterface $order
     *
     * @return PaymentInterface|null
     */
    private function getLastPayment(OrderInterface $order)
    {
        $lastCancelledPayment = $order->getLastPayment(PaymentInterface::STATE_CANCELLED);
        if (null !== $lastCancelledPayment) {
            return $lastCancelledPayment;
        }

        $lastFailedPayment = $order->getLastPayment(PaymentInterface::STATE_FAILED);
        if (null !== $lastFailedPayment) {
            return $lastFailedPayment;
        }

        return null;
    }

    /**
     * @param PaymentInterface $payment
     * @param OrderInterface $order
     *
     * @return PaymentMethodInterface|null
     */
    private function getDefaultPaymentMethod(PaymentInterface $payment, OrderInterface $order)
    {
        try {
            $payment->setOrder($order);
            $paymentMethod = $this->defaultPaymentMethodResolver->getDefaultPaymentMethod($payment);

            return $paymentMethod;
        } catch (UnresolvedDefaultPaymentMethodException $exception) {
            return null;
        }
    }

    /**
     * @param PaymentInterface $payment
     */
    private function applyRequiredTransition(PaymentInterface $payment)
    {
        if ($this->targetState === $payment->getState()) {
            return;
        }

        /** @var StateMachineInterface $stateMachine */
        $stateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        $targetTransition = $stateMachine->getTransitionToState($this->targetState);
        if (null !== $targetTransition) {
            $stateMachine->apply($targetTransition);
        }
    }
}
