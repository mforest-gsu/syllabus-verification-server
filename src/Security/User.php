<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Security;

use Gadget\Io\Cast;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    public static function create(mixed $claims): self
    {
        $claims = Cast::toArray($claims);
        return new self(
            userIdentifier: Cast::toString($claims['preferred_username'] ?? null),
            expiresOn: Cast::toInt($claims['exp'] ?? null),
            nonce: Cast::toString($claims['nonce'] ?? null),
            attributes: $claims
        );
    }


    /**
     * @param string $userIdentifier
     * @param int $expiresOn
     * @param string $nonce
     * @param mixed[] $attributes
     */
    public function __construct(
        private string $userIdentifier,
        private int $expiresOn,
        private string $nonce,
        private array $attributes
    ) {
    }


    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }


    public function getExpiresOn(): int
    {
        return $this->expiresOn;
    }


    public function getNonce(): string
    {
        return $this->nonce;
    }


    /** @return mixed[] */
    public function getAttributes(): array
    {
        return $this->attributes;
    }


    /** @return string[] */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }


    public function eraseCredentials(): void
    {
    }
}
