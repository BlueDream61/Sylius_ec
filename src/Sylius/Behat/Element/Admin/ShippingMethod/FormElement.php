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

namespace Sylius\Behat\Element\Admin\ShippingMethod;

use FriendsOfBehat\PageObjectExtension\Element\Element;
use Sylius\Behat\Service\DriverHelper;

final class FormElement extends Element implements FormElementInterface
{
    /**
     * @return array<string, string>
     */
    protected function getDefinedElements(): array
    {
        return [
            'amount' => '#sylius_shipping_method_configuration_%channelCode%_amount',
            'calculator' => '#sylius_shipping_method_calculator',
            'calculator_configuration_channel_tab' => '[data-test-calculator-configuration] [data-test-channel-tab="%channelCode%"]',
            'channel' => '[name="sylius_shipping_method[channels][]"][value="%channelCode%"]',
            'code' => '#sylius_shipping_method_code',
            'description' => '#sylius_shipping_method_translations_%localeCode%_description',
            'live_component' => '[data-controller="live"]',
            'name' => '#sylius_shipping_method_translations_%localeCode%_name',
            'position' => '#sylius_shipping_method_position',
            'zone' => '#sylius_shipping_method_zone',
        ];
    }

    public function getCode(): string
    {
        return $this->getElement('code')->getValue();
    }

    public function setCode(string $code): void
    {
        $this->getElement('code')->setValue($code);
    }

    public function getName(string $localeCode = 'en_US')
    {
        return $this->getElement('name', ['%localeCode%' => $localeCode])->getValue();
    }

    public function setName(string $name, string $localeCode = 'en_US'): void
    {
        $this->getElement('name', ['%localeCode%' => $localeCode])->setValue($name);
    }

    public function getPosition(): int
    {
        return (int) $this->getElement('position')->getValue();
    }

    public function setPosition(int $position): void
    {
        $this->getElement('position')->setValue($position);
    }

    public function getDescription(string $localeCode = 'en_US'): string
    {
        return $this->getElement('description', ['%localeCode%' => $localeCode])->getValue();
    }

    public function setDescription(string $description, string $localeCode = 'en_US'): void
    {
        $this->getElement('description', ['%localeCode%' => $localeCode])->setValue($description);
    }

    public function getZoneCode(): string
    {
        return $this->getElement('zone')->getValue();
    }

    public function setZoneCode(string $code): void
    {
        $this->getElement('zone')->setValue($code);
    }

    public function checkChannel(string $channelCode): void
    {
        $this->getElement('channel', ['%channelCode%' => $channelCode])->check();
    }

    public function hasCheckedChannel(string $channelCode): bool
    {
        return $this->getElement('channel', ['%channelCode%' => $channelCode])->isChecked();
    }

    public function setCalculatorConfigurationAmountForChannel(string $channelCode, int $amount): void
    {
        $this->selectCalculatorConfigurationChannelTab($channelCode);

        $this->getElement('amount', ['%channelCode%' => $channelCode])->setValue($amount);
    }

    public function chooseCalculator(string $calculatorName): void
    {
        $this->getElement('calculator')->selectOption($calculatorName);
        $this->waitForLiveComponentUpdate();
    }

    private function selectCalculatorConfigurationChannelTab(string $channelCode): void
    {
        if (!DriverHelper::isJavascript($this->getDriver())) {
            throw new \RuntimeException('This method can be used only with JavaScript enabled');
        }

        $this->getElement('calculator_configuration_channel_tab', ['%channelCode%' => $channelCode])->click();
    }

    private function waitForLiveComponentUpdate(): void
    {
        $form = $this->getElement('live_component');
        usleep(500000);
        $form->waitFor(1500, fn () => !$form->hasAttribute('busy'));
    }
}
