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

namespace Sylius\Bundle\ApiBundle\Controller;

use Sylius\Bundle\ApiBundle\Creator\ImageCreatorInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\Request;

final class UploadTaxonImageAction
{
    public function __construct(private ImageCreatorInterface $taxonImageCreator)
    {
    }

    public function __invoke(Request $request): ImageInterface
    {
        return $this->taxonImageCreator->create(
            $request->attributes->get('code', ''),
            $request->files->get('file'),
            $request->request->get('type'),
        );
    }
}
