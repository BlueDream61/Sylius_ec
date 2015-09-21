<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Sequence\Model;

interface SequenceInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return integer
     */
    public function getIndex();

    /**
     * Increment sequence type
     */
    public function incrementIndex();
}
