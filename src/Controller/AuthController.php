<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Controller;

use Gsu\SyllabusVerification\Entity\AuthorizeRequest;
use Gsu\SyllabusVerification\Security\AccessTokenHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class AuthController extends AbstractController
{
    /**
     * @param AccessTokenHandler $accessTokenHandler
     * @param string $authUri
     * @param string $clientId
     * @param string $webUri
     * @param string $apiUri
     */
    public function __construct(
        private AccessTokenHandler $accessTokenHandler,
        private string $authUri,
        private string $clientId,
        private string $webUri,
        private string $apiUri
    ) {
    }


    /**
     * @param string $state
     * @return string
     */
    private function createNonce(string $state): string
    {
        return hash('SHA256', http_build_query([
            "client_id" => $this->clientId,
            "response_type" => "id_token",
            "redirect_uri" => "{$this->apiUri}/authorize",
            "scope" => "openid profile",
            "response_mode" => "form_post",
            "state" => $state,
            "prompt" => "select_account"
        ], "", null, PHP_QUERY_RFC3986));
    }


    #[Route(methods: 'GET', path: '/authenticate')]
    public function authenticate(): Response
    {
        $state = bin2hex(random_bytes(32));
        return $this->redirect(sprintf(
            "%s?%s",
            $this->authUri,
            http_build_query([
                "client_id" => $this->clientId,
                "response_type" => "id_token",
                "redirect_uri" => "{$this->apiUri}/authorize",
                "scope" => "openid profile",
                "nonce" => $this->createNonce($state),
                "response_mode" => "form_post",
                "state" => $state,
                "prompt" => "select_account"
            ], "", null, PHP_QUERY_RFC3986)
        ));
    }


    #[Route(methods: 'POST', path: '/authorize')]
    public function authorize(#[MapRequestPayload] AuthorizeRequest $authRequest): Response
    {
        try {
            $user = $this->accessTokenHandler->decodeJwt(
                $authRequest->id_token,
                $this->createNonce($authRequest->state)
            );

            return $this->redirect("{$this->webUri}/?token=" . urlencode(json_encode([
                "userId" => $user->getUserIdentifier(),
                "expiresOn" => $user->getExpiresOn(),
                "accessToken" => $this->accessTokenHandler->encryptJwt($authRequest->id_token),
            ], JSON_THROW_ON_ERROR)));
        } catch (\Throwable $t) {
            throw new AccessDeniedException('Invalid JWT', $t);
        }
    }


    /**
     * @param string $token
     * @return Response
     */
    public function showToken(string $token): Response
    {
        return $this->json(json_decode($token, true, 512, JSON_THROW_ON_ERROR));
    }


    #[Route(methods: 'GET', path: '/check', format: 'json')]
    public function check(): Response
    {
        return $this->json($this->getUser() !== null);
    }
}
