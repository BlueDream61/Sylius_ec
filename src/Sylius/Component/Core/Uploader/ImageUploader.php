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

namespace Sylius\Component\Core\Uploader;

use enshrined\svgSanitize\Sanitizer;
use Sylius\Component\Core\Filesystem\Adapter\FilesystemAdapterInterface;
use Sylius\Component\Core\Filesystem\Exception\FileNotFoundException;
use Sylius\Component\Core\Generator\ImagePathGeneratorInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

class ImageUploader implements ImageUploaderInterface
{
    private const MIME_SVG_XML = 'image/svg+xml';

    private const MIME_SVG = 'image/svg';

    /** @var Sanitizer */
    protected $sanitizer;

    public function __construct(
        protected FilesystemAdapterInterface $filesystem,
        protected ImagePathGeneratorInterface $imagePathGenerator,
    ) {
        $this->sanitizer = new Sanitizer();
    }

    public function upload(ImageInterface $image): void
    {
        if (!$image->hasFile()) {
            return;
        }

        /** @var File $file */
        $file = $image->getFile();

        Assert::isInstanceOf($file, File::class);

        $fileContent = $this->sanitizeContent(file_get_contents($file->getPathname()), $file->getMimeType());

        if (null !== $image->getPath() && $this->filesystem->has($image->getPath())) {
            $this->remove($image->getPath());
        }

        do {
            $path = $this->imagePathGenerator->generate($image);
        } while ($this->isAdBlockingProne($path) || $this->filesystem->has($path));

        $image->setPath($path);

        $this->filesystem->write($image->getPath(), $fileContent);
    }

    public function remove(string $path): bool
    {
        try {
            $this->filesystem->delete($path);
        } catch (FileNotFoundException) {
            return false;
        }

        return true;
    }

    protected function sanitizeContent(string $fileContent, string $mimeType): string
    {
        if (self::MIME_SVG_XML === $mimeType || self::MIME_SVG === $mimeType) {
            $fileContent = $this->sanitizer->sanitize($fileContent);
        }

        return $fileContent;
    }

    /**
     * Will return true if the path is prone to be blocked by ad blockers
     */
    private function isAdBlockingProne(string $path): bool
    {
        return str_contains($path, 'ad');
    }
}
