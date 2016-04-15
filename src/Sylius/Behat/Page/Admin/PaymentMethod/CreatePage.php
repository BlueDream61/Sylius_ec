<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Admin\PaymentMethod;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Behaviour\ChecksCodeImmutability;
use Sylius\Behat\Behaviour\SpecifiesItsCode;
use Sylius\Behat\Behaviour\Toggles;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

/**
 * @author Grzegorz Sadowski <grzegorz.sadowski@lakion.com>
 */
class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ChecksCodeImmutability, Toggles, SpecifiesItsCode;

    /**
     * {@inheritdoc}
     */
    public function nameIt($name, $language)
    {
        $this->getDocument()->fillField(sprintf('sylius_payment_method_translations_%s_name', $language), $name);
    }

    /**
     * {@inheritdoc}
     */
    public function chooseGateway($gateway)
    {
        $this->getElement('gateway')->selectOption($gateway);
    }

    /**
     * {@inheritdoc}
     */
    public function describeIt($description, $language)
    {
        $this->getDocument()->fillField(sprintf('sylius_payment_method_translations_%s_description', $language), $description);
    }

    /**
     * {@inheritdoc}
     */
    public function isPaymentMethodEnabled()
    {
        return (bool) $this->getToggleableElement()->getValue();
    }

    /**
     * @return NodeElement
     *
     * @throws ElementNotFoundException
     */
    protected function getCodeElement()
    {
        return $this->getElement('code');
    }

    /**
     * @return NodeElement
     *
     * @throws ElementNotFoundException
     */
    protected function getToggleableElement()
    {
        return $this->getElement('enabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'code' => '#sylius_payment_method_code',
            'name' => '#sylius_payment_method_translations_en_US_name',
            'gateway' => '#sylius_payment_method_gateway',
            'enabled' => '#sylius_payment_method_enabled',
        ]);
    }
}
