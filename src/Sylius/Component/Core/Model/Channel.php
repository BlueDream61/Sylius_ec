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

namespace Sylius\Component\Core\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Channel\Model\Channel as BaseChannel;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Model\LocaleInterface;

class Channel extends BaseChannel implements ChannelInterface
{
    /** @var CurrencyInterface */
    protected $baseCurrency;

    /** @var LocaleInterface */
    protected $defaultLocale;

    /** @var ZoneInterface */
    protected $defaultTaxZone;

    /** @var string */
    protected $taxCalculationStrategy;

    /**
     * @var Collection|CurrencyInterface[]
     *
     * @psalm-var Collection<array-key, CurrencyInterface>
     */
    protected $currencies;

    /**
     * @var Collection|LocaleInterface[]
     *
     * @psalm-var Collection<array-key, LocaleInterface>
     */
    protected $locales;

    /**
     * @var Collection|CountryInterface[]
     *
     * @psalm-var Collection<array-key, CountryInterface>
     */
    protected $countries;

    /** @var string */
    protected $themeName;

    /** @var string */
    protected $contactEmail;

    /** @var bool */
    protected $skippingShippingStepAllowed = false;

    /** @var bool */
    protected $skippingPaymentStepAllowed = false;

    /** @var bool */
    protected $accountVerificationRequired = true;

    /** @var ShopBillingDataInterface|null */
    protected $shopBillingData;

    /** @var TaxonInterface|null */
    protected $menuTaxon;

    public function __construct()
    {
        parent::__construct();

        /** @var ArrayCollection<array-key, CurrencyInterface> $this->currencies */
        $this->currencies = new ArrayCollection();
        /** @var ArrayCollection<array-key, LocaleInterface> $this->locales */
        $this->locales = new ArrayCollection();
        /** @var ArrayCollection<array-key, CountryInterface> $this->countries */
        $this->countries = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseCurrency(): ?CurrencyInterface
    {
        return $this->baseCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseCurrency(?CurrencyInterface $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocale(): ?LocaleInterface
    {
        return $this->defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultLocale(?LocaleInterface $defaultLocale): void
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultTaxZone(): ?ZoneInterface
    {
        return $this->defaultTaxZone;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultTaxZone(?ZoneInterface $defaultTaxZone): void
    {
        $this->defaultTaxZone = $defaultTaxZone;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxCalculationStrategy(): ?string
    {
        return $this->taxCalculationStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxCalculationStrategy(?string $taxCalculationStrategy): void
    {
        $this->taxCalculationStrategy = $taxCalculationStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function addCurrency(CurrencyInterface $currency): void
    {
        if (!$this->hasCurrency($currency)) {
            $this->currencies->add($currency);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeCurrency(CurrencyInterface $currency): void
    {
        if ($this->hasCurrency($currency)) {
            $this->currencies->removeElement($currency);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasCurrency(CurrencyInterface $currency): bool
    {
        return $this->currencies->contains($currency);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocales(): Collection
    {
        return $this->locales;
    }

    /**
     * {@inheritdoc}
     */
    public function addLocale(LocaleInterface $locale): void
    {
        if (!$this->hasLocale($locale)) {
            $this->locales->add($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeLocale(LocaleInterface $locale): void
    {
        if ($this->hasLocale($locale)) {
            $this->locales->removeElement($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasLocale(LocaleInterface $locale): bool
    {
        return $this->locales->contains($locale);
    }

    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(CountryInterface $country): void
    {
        if (!$this->hasCountry($country)) {
            $this->countries->add($country);
        }
    }

    public function removeCountry(CountryInterface $country): void
    {
        if ($this->hasCountry($country)) {
            $this->countries->removeElement($country);
        }
    }

    public function hasCountry(CountryInterface $country): bool
    {
        return $this->countries->contains($country);
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeName(): ?string
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function setThemeName(?string $themeName): void
    {
        $this->themeName = $themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    /**
     * {@inheritdoc}
     */
    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * {@inheritdoc}
     */
    public function isSkippingShippingStepAllowed(): bool
    {
        return $this->skippingShippingStepAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function setSkippingShippingStepAllowed(bool $skippingShippingStepAllowed): void
    {
        $this->skippingShippingStepAllowed = $skippingShippingStepAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function isSkippingPaymentStepAllowed(): bool
    {
        return $this->skippingPaymentStepAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function setSkippingPaymentStepAllowed(bool $skippingPaymentStepAllowed): void
    {
        $this->skippingPaymentStepAllowed = $skippingPaymentStepAllowed;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountVerificationRequired(): bool
    {
        return $this->accountVerificationRequired;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountVerificationRequired(bool $accountVerificationRequired): void
    {
        $this->accountVerificationRequired = $accountVerificationRequired;
    }

    public function getShopBillingData(): ?ShopBillingDataInterface
    {
        return $this->shopBillingData;
    }

    public function setShopBillingData(ShopBillingDataInterface $shopBillingData): void
    {
        $this->shopBillingData = $shopBillingData;
    }

    public function getMenuTaxon(): ?TaxonInterface
    {
        return $this->menuTaxon;
    }

    public function setMenuTaxon(?TaxonInterface $menuTaxon): void
    {
        $this->menuTaxon = $menuTaxon;
    }
}
