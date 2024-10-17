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

namespace Sylius\Bundle\UserBundle\Mailer;

final class Emails
{
    public const RESET_PASSWORD_TOKEN = 'reset_password_token';

    /** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
    public const RESET_PASSWORD_PIN = 'reset_password_pin';

    public const EMAIL_VERIFICATION_TOKEN = 'verification_token';
}
