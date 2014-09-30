<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ContentBundle;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;

/**
 * Sylius content bundle.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusContentBundle extends AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getBundlePrefix()
    {
        return 'sylius_addressing';
    }
}
