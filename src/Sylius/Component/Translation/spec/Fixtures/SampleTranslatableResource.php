<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Translation\Fixtures;

use Sylius\Component\Translation\Model\TranslatableInterface;
use Sylius\Component\Translation\Model\TranslatableTrait;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class SampleTranslatableResource implements TranslatableInterface
{
    use TranslatableTrait;
}
