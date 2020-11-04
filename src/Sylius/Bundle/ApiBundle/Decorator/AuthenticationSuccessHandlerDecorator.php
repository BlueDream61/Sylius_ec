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

namespace Sylius\Bundle\ApiBundle\Decorator;

use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/** @experimental */
class AuthenticationSuccessHandlerDecorator implements AuthenticationSuccessHandlerInterface
{
    /** @var AuthenticationSuccessHandlerInterface */
    private $authenticationSuccessHandlerInterface;

    /** @var CartToUserAssignerInterface */
    private $cartToUserAssignerInterface;

    public function __construct(
        AuthenticationSuccessHandlerInterface $authenticationSuccessHandlerInterface,
        CartToUserAssignerInterface $cartToUserAssignerInterface
    ) {
        $this->authenticationSuccessHandlerInterface = $authenticationSuccessHandlerInterface;
        $this->cartToUserAssignerInterface = $cartToUserAssignerInterface;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        if ($token->getUser() instanceof ShopUserInterface) {
            /** @var ShopUserInterface $user */
            $user = $token->getUser();

            #TODO use CartBlamerListener maybe
            $this->cartToUserAssignerInterface->assignByCustomer($user->getCustomer());
        }

        return $this->authenticationSuccessHandlerInterface->handleAuthenticationSuccess($token->getUser());
    }
}
