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

namespace Sylius\Bundle\LocaleBundle\Form\DataTransformer;

use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Locale\Provider\LocaleCollectionProviderInterface;
use Symfony\Component\Form\DataTransformerInterface;

/** @phpstan-ignore-next-line */
final class LocaleToCodeTransformer implements DataTransformerInterface
{
    public function __construct(private LocaleCollectionProviderInterface $localesProvider)
    {
    }

    public function transform(mixed $value): string
    {
        if (!$value instanceof LocaleInterface) {
            throw new \InvalidArgumentException(
                sprintf('Value must be instance of %s. Got "%s"',
                    LocaleInterface::class,
                    is_object($value) ? get_class($value) : gettype($value)
                ),
            );
        }

        return $value->getCode();
    }

    public function reverseTransform(mixed $value): ?LocaleInterface
    {
        if (!is_string($value)) {
            return null;
        }

        return $this->getLocaleByCode($value);
    }

    private function getLocaleByCode(string $localeCode): LocaleInterface
    {
        $locales = $this->localesProvider->getAll();

        if (!isset($locales[$localeCode])) {
            throw new \InvalidArgumentException(sprintf('Locale with code "%s" does not exist.', $localeCode));
        }

        return $locales[$localeCode];
    }
}
