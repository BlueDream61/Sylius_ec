<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ChannelBundle\Development;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
final class FakeHostnamePersister
{
    /**
     * @var FakeHostnameProviderInterface
     */
    private $fakeHostnameProvider;

    /**
     * @param FakeHostnameProviderInterface $fakeHostnameProvider
     */
    public function __construct(FakeHostnameProviderInterface $fakeHostnameProvider)
    {
        $this->fakeHostnameProvider = $fakeHostnameProvider;
    }

    /**
     * @param FilterResponseEvent $filterResponseEvent
     */
    public function onKernelResponse(FilterResponseEvent $filterResponseEvent)
    {
        if (HttpKernelInterface::SUB_REQUEST === $filterResponseEvent->getRequestType()) {
            return;
        }

        $fakeHostname = $this->fakeHostnameProvider->getHostname($filterResponseEvent->getRequest());

        if (null === $fakeHostname) {
            return;
        }

        $response = $filterResponseEvent->getResponse();
        $response->headers->setCookie(new Cookie('_hostname', $fakeHostname));
    }
}
