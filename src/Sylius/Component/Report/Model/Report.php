<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Report\Model;

/**
 * @author Łukasz Chruściel <lchrusciel@gmail.com>
 * @author Mateusz Zalewski <zaleslaw@gmail.com>
 */

class Report implements ReportInterface
{
    /**
     *@var integer
     */
    private $id;

    /**
     * @var String
     */
    private $name;

    /**
     * @var String
     */
    private $description;

    /**
     * @var DataFetcher
     */
    private $dataFetcher;

    /**
     * @var Array
     */
    private $dataFetcherConfiguration;

    /**
     * Gets the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of dataFetcher.
     *
     * @return DataFetcher
     */
    public function getDataFetcher()
    {
        return $this->dataFetcher;
    }

    /**
     * Sets the value of dataFetcher.
     *
     * @param DataFetcher $dataFetcher the data fetcher
     *
     * @return self
     */
    public function setDataFetcher(DataFetcher $dataFetcher)
    {
        $this->dataFetcher = $dataFetcher;

        return $this;
    }

    /**
     * Gets the value of dataFetcherConfiguration.
     *
     * @return Array
     */
    public function getDataFetcherConfiguration()
    {
        return $this->dataFetcherConfiguration;
    }

    /**
     * Sets the value of dataFetcherConfiguration.
     *
     * @param Array $dataFetcherConfiguration the data fetcher configuration
     *
     * @return self
     */
    public function setDataFetcherConfiguration(Array $dataFetcherConfiguration)
    {
        $this->dataFetcherConfiguration = $dataFetcherConfiguration;

        return $this;
    }
}
