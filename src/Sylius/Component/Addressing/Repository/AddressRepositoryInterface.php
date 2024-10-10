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

namespace Sylius\Component\Addressing\Repository;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @template T of AddressInterface
 *
 * @extends RepositoryInterface<T>
 */
interface AddressRepositoryInterface extends RepositoryInterface
{
}
