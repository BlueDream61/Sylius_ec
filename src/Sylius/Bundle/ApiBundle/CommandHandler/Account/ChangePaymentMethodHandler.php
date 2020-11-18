<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ApiBundle\CommandHandler\Account;

use Sylius\Bundle\ApiBundle\Command\Account\ChangePaymentMethod;
use Sylius\Bundle\ApiBundle\Changer\PaymentMethodChangerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

/** @experimental */
final class ChangePaymentMethodHandler implements MessageHandlerInterface
{
    /** @var PaymentMethodChangerInterface */
    private $paymentMethodChanger;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    public function __construct(
        PaymentMethodChangerInterface $commandPaymentMethodChanger,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->paymentMethodChanger = $commandPaymentMethodChanger;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(ChangePaymentMethod $changePaymentMethod): OrderInterface
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneBy(['tokenValue' => $changePaymentMethod->orderTokenValue]);

        Assert::notNull($order, 'Cart has not been found.');

        return $this->paymentMethodChanger->changePaymentMethod(
            $changePaymentMethod->paymentMethodCode,
            $changePaymentMethod->paymentId,
            $order
        );
    }
}
