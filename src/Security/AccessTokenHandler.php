<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Security;

use Firebase\JWT\CachedKeySet;
use Firebase\JWT\JWT;
use Gadget\Io\Cast;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

final class AccessTokenHandler implements AccessTokenHandlerInterface
{
    /**
     * @param CachedKeySet $jwks
     * @param string $accessTokenKey
     */
    public function __construct(
        private CachedKeySet $jwks,
        private string $accessTokenKey
    ) {
    }


    /**
     * @param string $accessToken
     * @return UserBadge
     */
    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        try {
            $jwt = $this->decryptJwt($accessToken);
            $user = $this->decodeJwt($jwt);
            return new UserBadge(
                $user->getUserIdentifier(),
                fn (): User => $user,
                $user->getAttributes()
            );
        } catch (\Throwable $t) {
            throw new AccessDeniedException('Invalid JWT', $t);
        }
    }


    /**
     * @param string $jwt
     * @return string
     */
    public function encryptJwt(string $jwt): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        return base64_encode($nonce . sodium_crypto_secretbox(
            $jwt,
            $nonce,
            $this->accessTokenKey
        ));
    }


    /**
     * @param string $jwt
     * @return string
     */
    public function decryptJwt(string $jwt): string
    {
        $jwt = base64_decode($jwt, true);
        if (!is_string($jwt)) {
            throw new \RuntimeException();
        }

        $decryptedJwt = sodium_crypto_secretbox_open(
            mb_substr($jwt, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit'),
            mb_substr($jwt, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit'),
            $this->accessTokenKey
        );

        return is_string($decryptedJwt)
            ? $decryptedJwt
            : throw new \RuntimeException();
    }


    /**
     * @param string $jwt
     * @param string|null $nonce
     * @return User
     */
    public function decodeJwt(
        string $jwt,
        string|null $nonce = null
    ): User {
        JWT::$leeway = 30;
        $claims = Cast::toArray(JWT::decode($jwt, $this->jwks));
        $user = User::create($claims);
        if ($nonce !== null && $nonce !== $user->getNonce()) {
            throw new \RuntimeException();
        }
        return $user;
    }
}
