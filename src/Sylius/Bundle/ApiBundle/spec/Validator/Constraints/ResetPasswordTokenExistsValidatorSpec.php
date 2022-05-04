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

namespace spec\Sylius\Bundle\ApiBundle\Validator\Constraints;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ApiBundle\Validator\Constraints\ResetPasswordTokenExists;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ResetPasswordTokenExistsValidatorSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository): void
    {
        $this->beConstructedWith($userRepository);
    }

    function it_is_a_constraint_validator(): void
    {
        $this->shouldImplement(ConstraintValidatorInterface::class);
    }

    function it_throws_an_exception_if_value_is_not_a_string(): void
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('validate', [null, new class() extends Constraint {
            }])
        ;
    }

    function it_throws_an_exception_if_constraint_is_not_a_resetPasswordTokenExists_constraint(): void
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('validate', ['', new class() extends Constraint {
            }])
        ;
    }

    function it_does_not_add_violation_if_shop_user_exist(
        UserRepositoryInterface $userRepository,
        ExecutionContextInterface $executionContext,
        ShopUserInterface $shopUser
    ): void {
        $this->initialize($executionContext);

        $value = 'token';

        $userRepository->findOneBy(['passwordResetToken' => 'token'])->willReturn($shopUser);

        $executionContext
            ->addViolation('sylius.reset_password.invalid_token', ['%token%' => 'token'])
            ->shouldNotBeCalled();

        $this->validate($value, new ResetPasswordTokenExists());
    }

    function it_adds_violation_if_reset_password_token_exists(
        UserRepositoryInterface $userRepository,
        ExecutionContextInterface $executionContext
    ): void {
        $this->initialize($executionContext);

        $value = 'token';

        $userRepository->findOneBy(['passwordResetToken' => 'token'])->willReturn(null);

        $executionContext
            ->addViolation('sylius.reset_password.invalid_token', ['%token%' => 'token'])
            ->shouldBeCalled();

        $this->validate($value, new ResetPasswordTokenExists());
    }
}
