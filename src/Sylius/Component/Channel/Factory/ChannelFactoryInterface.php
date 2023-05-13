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

namespace Sylius\Component\Channel\Factory;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @template T of ChannelInterface
 * @extends FactoryInterface<T>
 */
interface ChannelFactoryInterface extends FactoryInterface
{
    public function createNamed(string $name): ChannelInterface;
}
