<?php

namespace Sylius\Component\Report\DataFetcher;

interface DataFetcherInterface
{
    /**
     * Fetching data from data base
     * 
     * @param array config
     * 
     * @return array of data
     */
    public function fetch(array $configuration);

    /**
     * 
     * @return Type of data
     */
    public function getType();
}