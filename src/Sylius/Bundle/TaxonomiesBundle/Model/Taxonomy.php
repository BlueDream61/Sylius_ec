<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\TaxonomiesBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for taxonomies.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Taxonomy implements TaxonomyInterface
{
    /**
     * Taxonomy id.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Taxonomy name.
     *
     * @var string
     */
    protected $name;

    /**
     * Root taxon.
     *
     * @var TaxonInterface
     */
    protected $root;

    /**
     * All taxons collection.
     *
     * @var Collection
     */
    protected $taxons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxons = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoot(TaxonInterface $root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxons()
    {
        return $this->root->getChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function hasTaxon(TaxonInterface $taxon)
    {
        return $this->root->hasTaxon($taxon);
    }

    /**
     * {@inheritdoc}
     */
    public function addTaxon(TaxonInterface $taxon)
    {
        if (!$this->hasTaxon($taxon)) {
            $taxon->setTaxonomy($this);
            $this->root->addTaxon($taxon);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTaxon(TaxonInterface $taxon)
    {
        if ($this->hasTaxon($taxon)) {
            $taxon->setTaxonomy(null);
            $this->root->removeTaxon($taxon);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->root->setName($name);

        return $this;
    }
}
