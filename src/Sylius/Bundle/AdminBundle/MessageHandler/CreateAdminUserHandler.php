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

namespace Sylius\Bundle\AdminBundle\MessageHandler;

use Sylius\Bundle\AdminBundle\Message\CreateAdminUser;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateAdminUserHandler implements MessageHandlerInterface
{
    private const GROUPS = ['sylius', 'sylius_user_create'];

    public function __construct(
        private UserRepositoryInterface $adminUserRepository,
        private FactoryInterface $adminUserFactory,
        private CanonicalizerInterface $canonicalizer,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(CreateAdminUser $command): CreateAdminUserResult
    {
        $adminUser = $this->setUpAdminUser($command);

        $constraintViolationList = $this->validator->validate($adminUser, null, self::GROUPS);

        if ($constraintViolationList->count()) {
            $violationMessages = $this->getViolationMessages($constraintViolationList);

            return new CreateAdminUserResult(true, $violationMessages);
        }

        $this->adminUserRepository->add($adminUser);

        return new CreateAdminUserResult(false);
    }

    private function setUpAdminUser(CreateAdminUser $command): AdminUserInterface
    {
        /** @var AdminUserInterface $adminUser */
        $adminUser = $this->adminUserFactory->createNew();

        $adminUser->setEmail($this->canonicalizer->canonicalize($command->getEmail()));
        $adminUser->setUsername($command->getUsername());
        $adminUser->setPlainPassword($command->getPlainPassword());
        $adminUser->setFirstName($command->getFirstName());
        $adminUser->setLastName($command->getLastName());
        $adminUser->setLocaleCode($command->getLocaleCode());
        $adminUser->setEnabled($command->isEnabled());

        return $adminUser;
    }

    private function getViolationMessages(ConstraintViolationListInterface $constraintViolationList): iterable
    {
        foreach ($constraintViolationList as $violation) {
            yield $violation->getMessage();
        }
    }
}
