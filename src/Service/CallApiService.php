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

        // Setting up token to connect to the Api
        // https://pole-emploi.io/data/documentation/utilisation-api-pole-emploi/generer-access-token
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

        $this->token = $token->toArray();
    }

    /**
     * Returning companies by commune_id and sector
     *
     * @param string $communeId
     * @param string $sector
     * @return array
     */
    public function getCompanies(string $communeId, string $sector, string $page): array
    {
        $specifiedCommuneId = 'commune_id=' . $communeId . '&rome_codes_keyword_search=' . $sector . '&page=' . $page;

        return $this->getApi($specifiedCommuneId);
    }

    /**
     * Api Call returning response
     * https://pole-emploi.io/data/documentation/utilisation-api-pole-emploi/requeter-api
     * @param string $parameters
     * @return array
     */
    public function getApi(string $parameters): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.emploi-store.fr/partenaire/labonneboite/v1/company/?' . $parameters,
            ['headers' => [
                'Authorization' => $this->token["token_type"] . ' ' .  $this->token["access_token"]
            ],]
        );

        return $response->toArray();
    }
}
