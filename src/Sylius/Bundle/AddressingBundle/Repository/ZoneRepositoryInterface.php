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

namespace Sylius\Bundle\AddressingBundle\Repository;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @template T of ZoneInterface
 *
 * @extends RepositoryInterface<T>
 */
interface ZoneRepositoryInterface extends RepositoryInterface
{
    /** @return ZoneInterface[] */
    public function findAllByAddress(AddressInterface $address, ?string $scope = null): array;
}
