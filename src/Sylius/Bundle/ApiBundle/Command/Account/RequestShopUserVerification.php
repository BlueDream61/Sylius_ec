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

namespace Sylius\Bundle\ApiBundle\Command\Account;

use Sylius\Bundle\ApiBundle\Attribute\ChannelCodeAware;
use Sylius\Bundle\ApiBundle\Attribute\LocaleCodeAware;
use Sylius\Bundle\ApiBundle\Attribute\ShopUserIdAware;
use Sylius\Bundle\ApiBundle\Command\IriToIdentifierConversionAwareInterface;

#[ShopUserIdAware]
#[ChannelCodeAware]
#[LocaleCodeAware]
readonly class RequestShopUserVerification implements IriToIdentifierConversionAwareInterface
{
    public function __construct(
        public mixed $shopUserId,
        public string $channelCode,
        public string $localeCode,
    ) {
    }
}
