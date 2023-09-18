<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @internal
 */

declare(strict_types=1);

use Symfony\Component\Routing\RouteCollection;

trigger_deprecation(
    'sylius/sylius',
    '1.3',
    'Importing files from Sylius/Sylius\'s "app/config" directory is deprecated since Sylius 1.3.',
);

return new RouteCollection();
