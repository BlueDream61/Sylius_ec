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

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Context\Ui\Admin\Helper\ValidationTrait;
use Sylius\Behat\Element\Admin\ShippingMethod\FormElementInterface;
use Sylius\Behat\Page\Admin\ShippingMethod\CreatePageInterface;
use Sylius\Behat\Page\Admin\ShippingMethod\IndexPageInterface;
use Sylius\Behat\Page\Admin\ShippingMethod\UpdatePageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Webmozart\Assert\Assert;

final class ManagingShippingMethodsContext implements Context
{
    use ValidationTrait;

    public function __construct(
        private IndexPageInterface $indexPage,
        private CreatePageInterface $createPage,
        private UpdatePageInterface $updatePage,
        private CurrentPageResolverInterface $currentPageResolver,
        private FormElementInterface $shippingMethodForm,
        private readonly SharedStorageInterface $sharedStorage,
    ) {
    }

    /**
     * @When I want to create a new shipping method
     */
    public function iWantToCreateANewShippingMethod(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I specify its code as :code
     * @When I do not specify its code
     */
    public function iSpecifyItsCodeAs(?string $code = null): void
    {
        $this->shippingMethodForm->setCode($code ?? '');
    }

    /**
     * @When I specify its position as :position
     */
    public function iSpecifyItsPositionAs(int $position): void
    {
        $this->shippingMethodForm->setPosition($position);
    }

    /**
     * @When I name it :name in :language
     * @When I rename it to :name in :language
     */
    public function iNameItIn(string $name, string $language): void
    {
        $this->shippingMethodForm->setName($name, $language);
    }

    /**
     * @When I describe it as :description in :language
     */
    public function iDescribeItAsIn(string $description, string $language): void
    {
        $this->shippingMethodForm->setDescription($description, $language);
    }

    /**
     * @When I define it for the zone named :zone
     */
    public function iDefineItForTheZone(ZoneInterface $zone): void
    {
        $this->shippingMethodForm->setZoneCode($zone->getCode());
    }

    /**
     * @When I make it available in channel :channel
     */
    public function iMakeItAvailableInChannel(ChannelInterface $channel): void
    {
        $this->shippingMethodForm->checkChannel($channel->getCode());
    }

    /**
     * @When I specify its amount as :amount for :channel channel
     */
    public function iSpecifyItsAmountForChannel(int $amount, ChannelInterface $channel): void
    {
        $this->shippingMethodForm->setCalculatorConfigurationAmountForChannel($channel->getCode(), $amount);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @When I choose :calculatorName calculator
     * @When I do not specify amount for :calculatorName calculator
     */
    public function iChooseCalculator(string $calculatorName): void
    {
        $this->shippingMethodForm->chooseCalculator($calculatorName);
    }

    /**
     * @When I check (also) the :shippingMethodName shipping method
     */
    public function iCheckTheShippingMethod(string $shippingMethodName): void
    {
        $this->indexPage->checkResourceOnPage(['name' => $shippingMethodName]);
    }

    /**
     * @When I delete them
     */
    public function iDeleteThem(): void
    {
        $this->indexPage->bulkDelete();
    }

    /**
     * @Then I should see the shipping method :shipmentMethodName in the list
     * @Then the shipping method :shipmentMethodName should appear in the registry
     * @Then the shipping method :shipmentMethodName should be in the registry
     */
    public function theShipmentMethodShouldAppearInTheRegistry(string $shipmentMethodName): void
    {
        $this->iWantToBrowseShippingMethods();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $shipmentMethodName]));
    }

    /**
     * @Then the shipping method :shipmentMethodName should not appear in the registry
     */
    public function theShipmentMethodShouldNotAppearInTheRegistry(string $shipmentMethodName): void
    {
        $this->iWantToBrowseShippingMethods();

        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $shipmentMethodName]));
    }

    /**
     * @Given /^(this shipping method) should still be in the registry$/
     */
    public function thisShippingMethodShouldStillBeInTheRegistry(ShippingMethodInterface $shippingMethod)
    {
        $this->theShipmentMethodShouldAppearInTheRegistry($shippingMethod->getName());
    }

    /**
     * @Then the shipping method :shippingMethod should be available in channel :channel
     */
    public function theShippingMethodShouldBeAvailableInChannel(
        ShippingMethodInterface $shippingMethod,
        ChannelInterface $channel,
    ): void {
        $this->iWantToModifyAShippingMethod($shippingMethod);

        Assert::true(
            $this->shippingMethodForm->hasCheckedChannel($channel->getCode()),
            sprintf(
                'Shipping method %s should be available in channel %s, but it is not.',
                $shippingMethod->getName(),
                $channel->getCode(),
            )
        );
    }

    /**
     * @Then I should be notified that shipping method with this code already exists
     */
    public function iShouldBeNotifiedThatShippingMethodWithThisCodeAlreadyExists()
    {
        Assert::same($this->shippingMethodForm->getValidationMessage('code'), 'The shipping method with given code already exists.');
    }

    /**
     * @Then there should still be only one shipping method with :element :code
     */
    public function thereShouldStillBeOnlyOneShippingMethodWith($element, $code)
    {
        $this->iWantToBrowseShippingMethods();

        Assert::true($this->indexPage->isSingleResourceOnPage([$element => $code]));
    }

    /**
     * @When I want to modify a shipping method :shippingMethod
     * @When /^I want to modify (this shipping method)$/
     */
    public function iWantToModifyAShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        $this->updatePage->open(['id' => $shippingMethod->getId()]);
    }

    /**
     * @Then I should not be able to edit its code
     */
    public function theCodeFieldShouldBeDisabled()
    {
        Assert::true($this->shippingMethodForm->isCodeDisabled());
    }

    /**
     * @Then /^(this shipping method) name should be "([^"]+)"$/
     * @Then /^(this shipping method) should still be named "([^"]+)"$/
     */
    public function thisShippingMethodNameShouldBe(ShippingMethodInterface $shippingMethod, $shippingMethodName)
    {
        $this->iWantToBrowseShippingMethods();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'code' => $shippingMethod->getCode(),
            'name' => $shippingMethodName,
        ]));
    }

    /**
     * @When I save my changes
     * @When I try to save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then /^I should be notified that (code) is required$/
     */
    public function iShouldBeNotifiedThatCodeIsRequired(string $field): void
    {
        Assert::same(
            $this->shippingMethodForm->getValidationMessage($field),
            sprintf('Please enter shipping method %s.', $field),
        );
    }

    /**
     * @Then I should be notified that name is required
     */
    public function iShouldBeNotifiedThatNameIsRequired($localeCode = 'en_US'): void
    {
        Assert::same(
            $this->shippingMethodForm->getValidationMessage('name', ['%localeCode%' => $localeCode]),
            'Please enter shipping method name.',
        );
    }

    /**
     * @Then I should be notified that code needs to contain only specific symbols
     */
    public function iShouldBeNotifiedThatCodeNeedsToContainOnlySpecificSymbols(): void
    {
        $this->assertFieldValidationMessage(
            'code',
            'Shipping method code can only be comprised of letters, numbers, dashes and underscores.',
        );
    }

    /**
     * @When I archive the :name shipping method
     */
    public function iArchiveTheShippingMethod(string $name): void
    {
        $this->indexPage->archiveShippingMethod($name);
    }

    /**
     * @When I restore the :name shipping method
     */
    public function iRestoreTheShippingMethod(string $name): void
    {
        $this->indexPage->restoreShippingMethod($name);
    }

    /**
     * @Then I should be viewing non archival shipping methods
     */
    public function iShouldBeViewingNonArchivalShippingMethods()
    {
        Assert::false($this->indexPage->isArchivalFilterEnabled());
    }

    /**
     * @Then I should see a single shipping method in the list
     * @Then I should see :numberOfShippingMethods shipping methods in the list
     * @Then I should see :numberOfShippingMethods shipping methods on the list
     */
    public function thereShouldBeNoShippingMethodsOnTheList(int $numberOfShippingMethods = 1): void
    {
        Assert::same($this->indexPage->countItems(), $numberOfShippingMethods);
    }

    /**
     * @Then the only shipping method on the list should be :name
     */
    public function theOnlyShippingMethodOnTheListShouldBe($name)
    {
        Assert::same((int) $this->indexPage->countItems(), 1);
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then shipping method with :element :name should not be added
     */
    public function shippingMethodWithElementValueShouldNotBeAdded($element, $name)
    {
        $this->iWantToBrowseShippingMethods();

        Assert::false($this->indexPage->isSingleResourceOnPage([$element => $name]));
    }

    /**
     * @When I do not name it
     */
    public function iDoNotNameIt()
    {
        // Intentionally left blank to fulfill context expectation
    }

    /**
     * @When I do not specify its zone
     */
    public function iDoNotSpecifyItsZone()
    {
        // Intentionally left blank to fulfill context expectation
    }

    /**
     * @When I remove its zone
     */
    public function iRemoveItsZone()
    {
        $this->updatePage->removeZone();
    }

    /**
     * @Then I should be notified that :element has to be selected
     * @Then I should be notified that the :element is required
     */
    public function iShouldBeNotifiedThatElementHasToBeSelected(string $element): void
    {
        $this->assertFieldValidationMessage($element, sprintf('Please select shipping method %s.', $element));
    }

    /**
     * @When I remove its name from :language translation
     */
    public function iRemoveItsNameFromTranslation($language)
    {
        $this->createPage->nameIt('', $language);
    }

    /**
     * @Given I am browsing shipping methods
     * @When I browse shipping methods
     * @When I want to browse shipping methods
     */
    public function iWantToBrowseShippingMethods()
    {
        $this->indexPage->open();
    }

    /**
     * @Given I am browsing archival shipping methods
     */
    public function iAmBrowsingArchivalShippingMethods()
    {
        $this->indexPage->open();
        $this->indexPage->chooseArchival('Yes');
        $this->indexPage->filter();
    }

    /**
     * @Given I filter archival shipping methods
     */
    public function iFilterArchivalShippingMethods()
    {
        $this->indexPage->chooseArchival('Yes');
        $this->indexPage->filter();
    }

    /**
     * @Then the first shipping method on the list should have :field :value
     */
    public function theFirstShippingMethodOnTheListShouldHave($field, $value)
    {
        $fields = $this->indexPage->getColumnFields($field);

        Assert::same(reset($fields), $value);
    }

    /**
     * @Then the last shipping method on the list should have :field :value
     */
    public function theLastShippingMethodOnTheListShouldHave($field, $value)
    {
        $fields = $this->indexPage->getColumnFields($field);

        Assert::same(end($fields), $value);
    }

    /**
     * @When I switch the way shipping methods are sorted :sortType by :field
     * @When I sort the shipping methods :sortType by :field
     * @Given the shipping methods are already sorted :sortType by :field
     */
    public function iSortShippingMethodsBy(string $sortType, string $field): void
    {
        $this->indexPage->sortBy($field);
    }

    /**
     * @When I enable it
     */
    public function iEnableIt()
    {
        $this->updatePage->enable();
    }

    /**
     * @When I disable it
     */
    public function iDisableIt()
    {
        $this->updatePage->disable();
    }

    /**
     * @Then /^(this shipping method) should be disabled$/
     */
    public function thisShippingMethodShouldBeDisabled(ShippingMethodInterface $shippingMethod)
    {
        Assert::true($this->indexPage->isShippingMethodDisabled($shippingMethod));
    }

    /**
     * @Then /^(this shipping method) should be enabled$/
     */
    public function thisShippingMethodShouldBeEnabled(ShippingMethodInterface $shippingMethod)
    {
        Assert::true($this->indexPage->isShippingMethodEnabled($shippingMethod));
    }

    /**
     * @When I delete shipping method :shippingMethod
     * @When I try to delete shipping method :shippingMethod
     */
    public function iDeleteShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        $this->indexPage->deleteShippingMethod($shippingMethod->getName());
    }

    /**
     * @Then /^(this shipping method) should no longer exist in the registry$/
     */
    public function thisShippingMethodShouldNoLongerExistInTheRegistry(ShippingMethodInterface $shippingMethod)
    {
        Assert::false($this->indexPage->isSingleResourceOnPage(['code' => $shippingMethod->getCode()]));
    }

    /**
     * @Then I should be notified that amount for :channel channel should not be blank
     */
    public function iShouldBeNotifiedThatAmountForChannelShouldNotBeBlank(ChannelInterface $channel)
    {
        Assert::same(
            $this->shippingMethodForm->getValidationMessageForCalculatorConfiguration('amount', $channel->getCode()),
            'This value should not be blank.',
        );
    }

    /**
     * @Then I should be notified that shipping charge for :channel channel cannot be lower than 0
     */
    public function iShouldBeNotifiedThatShippingChargeForChannelCannotBeLowerThan0(ChannelInterface $channel): void
    {
        Assert::same(
            $this->shippingMethodForm->getValidationMessageForCalculatorConfiguration('amount', $channel->getCode()),
            'Shipping charge cannot be lower than 0.',
        );
    }

    /**
     * @When I add the "Total weight greater than or equal" rule configured with :weight
     */
    public function iAddTheTotalWeightGreaterThanOrEqualRuleConfiguredWith(int $weight): void
    {
        $this->shippingMethodForm->addRule('Total weight greater than or equal');
        $this->shippingMethodForm->fillLastRuleOption('Weight', (string) $weight);
    }

    /**
     * @When I add the "Total weight greater than or equal" rule configured with invalid data
     */
    public function iAddTheTotalWeightGreaterThanOrEqualRuleConfiguredWithInvalidData(): void
    {
        $this->shippingMethodForm->addRule('Total weight greater than or equal');
        $this->shippingMethodForm->fillLastRuleOption('Weight', 'invalid data');
    }

    /**
     * @When I add the "Total weight less than or equal" rule configured with :weight
     */
    public function iAddTheTotalWeightLessThanOrEqualRuleConfiguredWith(int $weight): void
    {
        $this->shippingMethodForm->addRule('Total weight less than or equal');
        $this->shippingMethodForm->fillLastRuleOption('Weight', (string) $weight);
    }

    /**
     * @When /^I add the "([^"]+)" rule configured with (?:€|£|\$)([^"]+) for ("[^"]+" channel)$/
     */
    public function iAddTheItemsTotalLessThanOrEqualRuleConfiguredWith(string $rule, mixed $value, ChannelInterface $channel): void
    {
        $this->shippingMethodForm->addRule($rule);
        $this->shippingMethodForm->fillLastRuleOptionForChannel($channel->getCode(), 'Amount', (string) $value);
    }

    /**
     * @When /^I add the "Items total less than or equal" rule configured with invalid data for ("[^"]+" channel)$/
     */
    public function iAddTheItemsTotalLessThanOrEqualRuleConfiguredWithInvalidData(ChannelInterface $channel): void
    {
        $this->shippingMethodForm->addRule('Items total less than or equal');
        $this->shippingMethodForm->fillLastRuleOptionForChannel($channel->getCode(), 'Amount', 'Invalid data');
    }

    /**
     * @When /^I remove the shipping charges of ("[^"]+" channel)$/
     */
    public function iRemoveTheShippingChargesOfChannel(ChannelInterface $channel): void
    {
        $this->updatePage->removeShippingChargesAmount($channel->getCode());
    }

    /**
     * @Then /^I should see that the shipping charges for ("[^"]+" channel) has (\d+) validation errors?$/
     */
    public function iShouldSeeThatTheShippingChargesForChannelHasCountValidationErrors(
        ChannelInterface $channel,
        int $count,
    ): void {
        Assert::same(
            $this->shippingMethodForm->getShippingChargesValidationErrorsCount($channel->getCode()),
            $count,
        );
    }

    /**
     * @Then I should be notified that the weight rule has an invalid configuration
     */
    public function iShouldBeNotifiedThatTheWeightRuleHasAnInvalidConfiguration(): void
    {
        $channel = $this->sharedStorage->get('channel');
        Assert::same(
            $this->shippingMethodForm->getValidationMessageForLastRuleConfiguration('weight'),
            'Please enter a number.',
        );
    }

    /**
     * @Then I should be notified that the amount rule has an invalid configuration in :channel channel
     */
    public function iShouldBeNotifiedThatTheAmountRuleHasAnInvalidConfigurationInChannel(ChannelInterface $channel): void
    {
        Assert::same(
            $this->shippingMethodForm->getValidationMessageForLastRuleConfiguration('amount', $channel->getCode()),
            'Please enter a valid money amount.',
        );
    }

    private function assertFieldValidationMessage(string $element, string $expectedMessage): void
    {
        Assert::same($this->shippingMethodForm->getValidationMessage($element), $expectedMessage);
    }

    protected function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);
    }
}
