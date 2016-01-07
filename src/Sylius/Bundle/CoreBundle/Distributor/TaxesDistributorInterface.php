<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Distributor;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
interface TaxesDistributorInterface
{
    /**
     * @param int $taxItems
     * @param float $taxAmount
     *
     * @return array
     */
    public function distribute($taxItems, $taxAmount);
}
