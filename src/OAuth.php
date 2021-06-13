<?php

namespace Mnt\OAuth;

use GuzzleHttp\Client;

class OAuth {

    public Client $guzzleClient;

    private string $client_id;
    private string $client_secret;
    private string $redirect_uri;
    private string $response_type;
    private $scope;

    private string $authorizationEndpoint;
    private string $accessTokenEndpoint;
    private string $userInfoEndpoint;

    private string $code;
    private string $grant_type;
    private string $token;
    private string $resource;

    public function __construct($params) {

        $this->guzzleClient = new Client([
            'timeout' => 3,
            'verify'  => realpath(dirname(__FILE__) . '/..') . '/cacert.pem'
        ]);

        $this->client_id             = $params['clientId'];
        $this->client_secret         = $params['clientSecret'];
        $this->redirect_uri          = $params['redirectUri'];

        $this->authorizationEndpoint = $params['authorizationEndpoint'];
        $this->accessTokenEndpoint   = $params['accessTokenEndpoint'];
        $this->userInfoEndpoint      = $params['userInfoEndpoint'];

        $this->scope                 = isset($params['scope']) ? $params['scope'] : ['email'];
        $this->response_type         = 'code';
        $this->grant_type            = 'authorization_code';
    }

    public function getAuthUrl() {

        return $this->authorizationEndpoint . '?response_type=' . $this->response_type . '&scope=' . $this->getScope() . '&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri;
    }

    public function getToken($code) {
        try {
            $this->code = $code;
            $response = $this->guzzleClient->request('POST', $this->accessTokenEndpoint, [
                'form_params' => [
                    'code'          => $this->code,
                    'client_id'     => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'redirect_uri'  => $this->redirect_uri,
                    'grant_type'    => $this->grant_type
                ]
            ]);
            $this->token = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
        return $this->token;
    }

    public function getResource() {
        try {
            $response = $this->guzzleClient->request('GET', $this->userInfoEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken()
                ]
            ]);
            $this->resource = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
        return $this->resource;
    }

    private function getScope() {
        $this->scope = implode('%20', $this->scope);
        return $this->scope;
    }
    private function getAccessToken() {
        $token = json_decode($this->token);
        return $token->access_token;
    }

}