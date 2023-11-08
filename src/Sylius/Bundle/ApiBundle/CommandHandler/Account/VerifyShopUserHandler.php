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

namespace Sylius\Bundle\ApiBundle\CommandHandler\Account;

use InvalidArgumentException;
use Sylius\Bundle\ApiBundle\Command\Account\SendAccountRegistrationEmail;
use Sylius\Bundle\ApiBundle\Command\Account\VerifyShopUser;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

/** @experimental  */
final class VerifyShopUserHandler implements MessageHandlerInterface
{
    /** @param RepositoryInterface<ShopUserInterface> $shopUserRepository */
    public function __construct(
        private RepositoryInterface $shopUserRepository,
        private ClockInterface $clock,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(VerifyShopUser $command): JsonResponse
    {
        /** @var ShopUserInterface|null $user */
        $user = $this->shopUserRepository->findOneBy(['emailVerificationToken' => $command->getToken()]);
        if (null === $user) {
            throw new InvalidArgumentException(
                sprintf('There is no shop user with %s email verification token', $command->getToken()),
            );
        }

        $user->setVerifiedAt($this->clock->now());
        $user->setEmailVerificationToken(null);
        $user->enable();

        $this->commandBus->dispatch(
            new SendAccountRegistrationEmail($user->getEmail(), $command->getLocaleCode(), $command->getChannelCode()),
            [new DispatchAfterCurrentBusStamp()],
        );

        return new JsonResponse([]);
    }
}
