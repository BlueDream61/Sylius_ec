<?php

declare(strict_types=1);

namespace Sylius\Bundle\ApiBundle\Command;

class ResetPasswordRequest implements ChannelCodeAwareInterface, LocaleCodeAwareInterface
{
    /** @var string */
    public $email;

    /** @var string|null */
    private $channelCode;

    /** @var string|null */
    private $localeCode;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(?string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }
}
