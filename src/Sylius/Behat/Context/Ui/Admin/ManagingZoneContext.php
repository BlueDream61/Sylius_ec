<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface;
use Sylius\Behat\Page\Admin\Zone\UpdatePageInterface;
use Sylius\Behat\Page\Admin\Zone\CreatePageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class ManagingZoneContext implements Context
{
    const RESOURCE_NAME = 'zone';

    /**
     * @var CreatePageInterface
     */
    private $createPage;

    /**
     * @var IndexPageInterface
     */
    private $indexPage;

    /**
     * @var UpdatePageInterface
     */
    private $updatePage;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    /**
     * @param CreatePageInterface $createPage
     * @param IndexPageInterface $indexPage
     * @param UpdatePageInterface $updatePage
     * @param NotificationCheckerInterface $notificationChecker
     */
    public function __construct(
        CreatePageInterface $createPage,
        IndexPageInterface $indexPage,
        UpdatePageInterface $updatePage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->createPage = $createPage;
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @Given I want to create a new zone with :memberType members
     */
    public function iWantToCreateANewZoneWithMembers($memberType)
    {
        $this->createPage->open(['type' => $memberType]);
    }

    /**
     * @When I name it :name
     */
    public function iNameIt($name)
    {
        $this->createPage->nameIt($name);
    }

    /**
     * @When I specify its code as :code
     */
    public function iSpecifyItsCodeAs($code)
    {
        $this->createPage->specifyCode($code);
    }

    /**
     * @When I add a country :name
     * @When I add a province :name
     * @When I add a zone :name
     */
    public function iAddACountry($name)
    {
        $this->createPage->addMember();
        $this->createPage->chooseMember($name);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @Then I should be notified about successful creation
     */
    public function iShouldBeNotifiedAboutSuccessfulCreation()
    {
        $this->notificationChecker->checkCreationNotification(self::RESOURCE_NAME);
    }

    /**
     * @Then /^the (zone named "[^"]+") with (the "[^"]+" country member) should appear in the registry$/
     * @Then /^the (zone named "[^"]+") with (the "[^"]+" province member) should appear in the registry$/
     * @Then /^the (zone named "[^"]+") with (the "[^"]+" zone member) should appear in the registry$/
     */
    public function theZoneWithTheCountryShouldAppearInTheRegistry(ZoneInterface $zone, ZoneMemberInterface $zoneMember)
    {
        $expectedZoneValues = [
            'code' => $zone->getCode(),
            'name' => $zone->getName(),
        ];

        Assert::true(
            $this->updatePage->isOpen(['id' => $zone->getId()]),
            sprintf('After successful creation we should be redirect to update page, but we are not!')
        );

        Assert::true(
            $this->updatePage->hasResourceValues($expectedZoneValues),
            sprintf('Zone %s is not valid', $zone->getName())
        );

        Assert::true(
            $this->updatePage->hasMember($zoneMember),
            sprintf('Zone %s has not %s zone member', $zone->getName(), $zoneMember->getCode())
        );
    }
}
