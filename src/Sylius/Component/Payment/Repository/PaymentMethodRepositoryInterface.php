<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Payment\Repository;

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
interface PaymentMethodRepositoryInterface
{
    /**
     * @return array
     */
    public function getQueryBuidlerByStatus($disabled);
}