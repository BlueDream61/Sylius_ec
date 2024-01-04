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

namespace Sylius\Component\Product\Repository;

use Sylius\Component\Product\Model\ProductTranslationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @template T of ProductTranslationInterface
 *
 * @extends RepositoryInterface<T>
 */
interface ProductTranslationRepositoryInterface extends RepositoryInterface
{
}
