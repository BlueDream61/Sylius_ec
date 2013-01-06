<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ShippingBundle\Model;

/**
 * Shipping rate interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 */
interface ShippingMethodInterface
{
    // Shippables requirement to match given method.
    const REQUIREMENT_MATCH_NONE = 0;
    const REQUIREMENT_MATCH_ANY  = 1;
    const REQUIREMENT_MATCH_ALL  = 2;

    /**
     * Get shipping method identifier.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get shipping category, if any.
     *
     * @return null|ShippingCategoryInterface
     */
    public function getCategory();

    /**
     * Set shipping category.
     *
     * @param null|ShippingCategoryInterface $category
     */
    public function setCategory(ShippingCategoryInterface $category = null);

    /**
     * Get the one of matching requirements.
     * For example, a method can apply to shipment on 3 different conditions.
     *
     * 1) None of shippables matches the category.
     * 2) At least one of shippables matches the category.
     * 3) All shippables have to match the method category.
     *
     * @return integer
     */
    public function getRequirement();

    /**
     * Set the requirement.
     *
     * @param integer $requirement
     */
    public function setRequirement($requirement);

    /**
     * Get the human readable of requirement.
     *
     * @return string
     */
    public function getRequirementLabel();

    /**
     * Check whether this method matches given shipment.
     *
     * @param ShipmentInterface $shipment
     *
     * @return Boolean
     */
    public function matches(ShipmentInterface $shipment);

    /**
     * Check whether the shipping method is currently enabled.
     *
     * @return Boolean
     */
    public function isEnabled();

    /**
     * Enable or disable the shipping method.
     *
     * @param Boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * Get shipping method name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get calculator name assigned for this shipping method.
     *
     * @return string
     */
    public function getCalculator();

    /**
     * Set calculator name assigned for this shipping method.
     *
     * @param string $calculator
     */
    public function setCalculator($calculator);

    /**
     * Get any extra configuration for calculator.
     *
     * @return array
     */
    public function getConfiguration();

    /**
     * Set extra configuration for calculator.
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration);

    /**
     * Get creation time.
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Get last update time.
     *
     * @return DateTime
     */
    public function getUpdatedAt();
}
