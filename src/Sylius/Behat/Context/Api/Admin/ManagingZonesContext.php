<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Api\Admin;

use ApiPlatform\Core\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ProvinceInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;
use Webmozart\Assert\Assert;

final class ManagingZonesContext implements Context
{
    /** @var ApiClientInterface */
    private $client;

    /** @var ResponseCheckerInterface */
    private $responseChecker;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var IriConverterInterface */
    private $iriConverter;

    public function __construct(
        ApiClientInterface $client,
        ResponseCheckerInterface $responseChecker,
        SharedStorageInterface $sharedStorage,
        IriConverterInterface $iriConverter
    ) {
        $this->client = $client;
        $this->responseChecker = $responseChecker;
        $this->sharedStorage = $sharedStorage;
        $this->iriConverter = $iriConverter;
    }

    /**
     * @When I want to create a new zone consisting of :memberType
     */
    public function iWantToCreateANewZoneConsistingOfCountry(string $memberType): void
    {
        $this->client->buildCreateRequest();
        $this->client->addRequestData('type', $memberType);
    }

    /**
     * @When I name it :name
     */
    public function iNameIt(string $name): void
    {
        $this->client->addRequestData('name', $name);
    }

    /**
     * @When I specify its code as :code
     */
    public function iSpecifyItsCodeAs(string $code): void
    {
        $this->client->addRequestData('code', $code);
    }

    /**
     * @When I add a country :country
     */
    public function iAddACountry(CountryInterface $country): void
    {
        $this->client->addSubResourceData('members', [
            'code' => $country->getCode(),
        ]);
    }

    /**
     * @When I add a province :province
     */
    public function iAddAProvince(ProvinceInterface $province): void
    {
        $this->client->addSubResourceData('members', [
            'code' => $province->getCode(),
        ]);
    }

    /**
     * @When I add a zone :zoneName
     */
    public function iAddAZone(ZoneInterface $zoneName): void
    {
        $this->client->addSubResourceData('members', [
            'code' => $zoneName->getCode(),
        ]);
    }

    /**
     * @When I select its scope as :scope
     */
    public function iSelectItsScopeAs(string $scope): void
    {
        $this->client->addRequestData('scope', $scope);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->client->create();
    }

    /**
     * @When I want to see all zones in store
     * @When I browse zones
     */
    public function iWantToSeeAllZonesInStore(): void
    {
        $this->client->index();
    }

    /**
     * @When I delete zone named :zoneName
     */
    public function iDeleteZoneNamed(ZoneInterface $zoneName): void
    {
        $this->client->delete($zoneName->getCode());
    }

    /**
     * @When I check the :zoneName zone
     * @When I check also the :zoneName zone
     */
    public function iCheckTheZone(ZoneInterface $zoneName): void
    {
        $ZoneToDelete = [];
        if ($this->sharedStorage->has('zone_to_delete')) {
            $ZoneToDelete = $this->sharedStorage->get('zone_to_delete');
        }
        $ZoneToDelete[] = $zoneName->getCode();
        $this->sharedStorage->set('zone_to_delete', $ZoneToDelete);
    }

    /**
     * @When I delete them
     */
    public function iDeleteThem(): void
    {
        foreach ($this->sharedStorage->get('zone_to_delete') as $code) {
            $this->client->delete($code);
        }
    }

    /**
     * @When I want to modify the zone named :zoneName
     */
    public function iWantToModifyTheZoneNamed(ZoneInterface $zoneName)
    {
        $this->client->buildUpdateRequest($zoneName->getCode());
    }

    /**
     * @When I remove the :country country member
     */
    public function iRemoveTheCountryMember(CountryInterface $country)
    {
        /** @var ZoneInterface $zone */
        $zone = $this->sharedStorage->get('zone');
        $countryIri = $this->iriConverter->getIriFromItem($country);

        $members = $this->responseChecker->getValue($this->client->show($zone->getCode(), 'members'));
        foreach ($members as $key => $member) {
            if ($countryIri === $member) {
                $this->client->removeSubResource('members', $member);
            }
        }
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->client->update();
    }

    /**
     * @Then the zone named :zoneName with the :country country member should appear in the registry
     */
    public function theZoneNamedWithTheCountryMemberShouldAppearInTheRegistry(ZoneInterface $zoneName, CountryInterface $country): void
    {
        Assert::true($this->responseChecker->hasItemWithValue(
            $this->client->subResourceIndex('members', $zoneName->getCode()),
            'code',
            $country->getCode()
        ));
    }

    /**
     * @Then the zone named :zoneName with the :province province member should appear in the registry
     */
    public function theZoneNamedWithTheProvinceMemberShouldAppearInTheRegistry(ZoneInterface $zoneName, ProvinceInterface $province): void
    {
        Assert::true($this->responseChecker->hasItemWithValue(
            $this->client->subResourceIndex('members', $zoneName->getCode()),
            'code',
            $province->getCode()
        ));
    }

    /**
     * @Then the zone named :zoneNamed with the :zoneName zone member should appear in the registry
     */
    public function theZoneNamedWithTheZoneMemberShouldAppearInTheRegistry(ZoneInterface $zoneNamed, ZoneInterface $zoneName): void
    {
        Assert::true($this->responseChecker->hasItemWithValue(
            $this->client->subResourceIndex('members', $zoneNamed->getCode()),
            'code',
            $zoneName->getCode()
        ));
    }

    /**
     * @Then its scope should be :scope
     */
    public function itsScopeShouldBe(string $scope): void
    {
        Assert::true(
            $this->responseChecker->hasValue($this->client->show('EU'), 'scope', $scope),
            sprintf('Its Zone does not have %s scope', $scope)
        );
    }

    /**
     * @Then I should see :count zones in the list
     * @Then I should see a single zone in the list
     */
    public function iShouldSeeZonesInTheList(int $count = 1): void
    {
        Assert::same($this->responseChecker->countCollectionItems($this->client->index()), $count);
    }

    /**
     * @Then I should see the zone named :name in the list
     * @Then I should still see the zone named :name in the list
     */
    public function iShouldSeeTheZoneNamedInTheList(string $name): void
    {
        Assert::true(
            $this->responseChecker->hasItemWithValue($this->client->index(), 'name', $name),
            sprintf('There is no zone with name "%s"', $name)
        );
    }

    /**
     * @Then the zone named :name should no longer exist in the registry
     */
    public function theZoneNamedShouldNoLongerExistInTheRegistry(string $name): void
    {
        Assert::false(
            $this->responseChecker->hasItemWithValue($this->client->index(), 'name', $name),
            sprintf('Zone with name %s exist', $name)
        );
    }

    /**
     * @Then /^(this zone) should have only (the "([^"]*)" (?:country|province|zone) member)$/
     */
    public function thisZoneShouldHaveOnlyTheProvinceMember(ZoneInterface $zone, ZoneMemberInterface $zoneMember)
    {
        Assert::true($this->responseChecker->hasItemWithValue(
            $this->client->subResourceIndex('members', $zone->getCode()),
            'code',
            $zoneMember->getCode()
        ));

        Assert::same($this->responseChecker->countCollectionItems($this->client->index()), 1);
    }

    /**
     * @Then I should be notified that it has been successfully created
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyCreated(): void
    {
        Assert::true(
            $this->responseChecker->isCreationSuccessful($this->client->getLastResponse()),
            'Zone could not be created'
        );
    }

    /**
     * @Then I should be notified that it has been successfully edited
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyEdited()
    {
        Assert::true(
            $this->responseChecker->isUpdateSuccessful($this->client->getLastResponse()),
            'Zone could not be edited'
        );
    }

    /**
     * @Then I should be notified that it has been successfully deleted
     * @Then I should be notified that they have been successfully deleted
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyDeleted(): void
    {
        Assert::true($this->responseChecker->isDeletionSuccessful(
            $this->client->getLastResponse()),
            'Zone could not be deleted'
        );
    }

    /**
     * @Then I should be notified that this zone cannot be deleted
     */
    public function iShouldBeNotifiedThatThisZoneCannotBeDeleted(): void
    {
        Assert::false(
            $this->responseChecker->isDeletionSuccessful($this->client->getLastResponse()),
            'Zone can be deleted, but it should not'
        );
    }
}
