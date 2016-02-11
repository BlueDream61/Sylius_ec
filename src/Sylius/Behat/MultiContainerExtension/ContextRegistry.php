<?php

namespace Sylius\Behat\MultiContainerExtension;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
final class ContextRegistry
{
    /**
     * @var array
     */
    private $registry;

    /**
     * @param string $serviceId
     * @param string $serviceClass
     */
    public function add($serviceId, $serviceClass)
    {
        $this->registry[$serviceId] = $serviceClass;
    }

    /**
     * @param string $serviceId
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getClass($serviceId)
    {
        if (!isset($this->registry[$serviceId])) {
            throw new \InvalidArgumentException();
        }

        return $this->registry[$serviceId];
    }
}
