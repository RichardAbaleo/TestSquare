<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    private $clientId;

    private $clientSecret;

    private $grantType;

    private $scope;

    private $token;

    /**
     * Credentials
     *
     * @param HttpClientInterface $client
     * @param string $clientId
     * @param string $clientSecret
     * @param string $grantType
     * @param string $scope
     */
    public function __construct(HttpClientInterface $client, $clientId, $clientSecret, $grantType, $scope)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->grantType = $grantType;
        $this->scope = $scope;
    }

    public function getApi(string $parameters): array
    {

        $token = $this->client->request(
            'POST',
            'https://entreprise.pole-emploi.fr/connexion/oauth2/access_token?realm=%2Fpartenaire',
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => [
                    'grant_type' => $this->grantType,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => $this->scope
                ]
            ]
        );

        $token = $token->toArray();

        $response = $this->client->request(
            'GET',
            'https://api.emploi-store.fr/partenaire/labonneboite/v1/company/?' . $parameters,
            ['headers' => [
                'Authorization' => $token["token_type"] . ' ' .  $token["access_token"]
            ],]
        );

        dd($response->toArray());

        return $response->toArray();
    }
}
