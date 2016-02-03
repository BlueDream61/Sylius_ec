<?php

/*
 * This file is part of the Sylius package.
 *
 * (c); Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Variation\Model;

use Sylius\Component\Product\Model\OptionValueTranslationInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface OptionValueInterface extends CodeAwareInterface, OptionValueTranslationInterface
{
    /**
     * @return OptionInterface
     */
    public function getOption();

    /**
     * Get internal value.
     *
     * @return string
     */
    public function getValue();

    /**
     * @param OptionInterface $option
     */
    public function setOption(OptionInterface $option = null);

    /**
     * Proxy method to access the name of real option object.
     * Those methods are mostly useful in templates so you can easily
     * display option name with their values.
     *
     * @return string The name of option
     */
    public function getName();

    /**
     * Proxy method to access the presentation of real option value object.
     *
     * @return string The presentation of object
     */
    public function getPresentation();

    /**
     * Proxy method to access the presentation of real option object.
     *
     * @return string The presentation of object
     */
    public function getPresentationOption();
}