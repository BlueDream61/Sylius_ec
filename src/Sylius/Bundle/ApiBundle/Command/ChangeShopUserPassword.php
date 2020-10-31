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

namespace Sylius\Bundle\ApiBundle\Command;

/** @experimental */
class ChangeShopUserPassword implements ShopUserIdAwareInterface
{
    /** @psalm-suppress MissingReturnType */
    public $shopUserId;

    /**
     * @var string|null
     * @psalm-immutable
     */
    public $newPassword;

    /**
     * @var string|null
     * @psalm-immutable
     */
    public $confirmPassword;

    /**
     * @var string|null
     * @psalm-immutable
     */
    public $currentPassword;

    public function __construct(?string $newPassword, ?string $confirmNewPassword, ?string $currentPassword)
    {
        $this->newPassword = $newPassword;
        $this->confirmPassword = $confirmNewPassword;
        $this->currentPassword = $currentPassword;
    }

    public function getShopUserId()
    {
        return $this->shopUserId;
    }

    public function setShopUserId($shopUserId): void
    {
        $this->shopUserId = $shopUserId;
    }
}
