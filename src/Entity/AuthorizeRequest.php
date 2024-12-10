<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

final class AuthorizeRequest
{
    /**
     * @param string $id_token
     * @param string $state
     * @param string $session_state
     */
    public function __construct(
        public string $id_token,
        public string $state,
        public string $session_state
    ) {
    }
}
