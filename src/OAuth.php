<?php

namespace Mnt\OAuth;

use GuzzleHttp\Client;

class OAuth {

    public Client $guzzleClient;

    private string $url_auth;
    private string $url_token;
    private string $client_id;
    private string $client_secret;
    private $scope;
    private string $redirect_uri;
    private string $response_type;

    private string $code;
    private string $grant_type;
    private string $token;
    private string $resource;

    public function __construct($params) {

        $this->guzzleClient = new Client();

        $this->client_id     = $params['clientId'];
        $this->client_secret = $params['clientSecret'];
        $this->redirect_uri  = $params['redirectUri'];
        $this->state         = md5(microtime(rand()));

        $this->url_auth      = $params['urlAuthorize'];
        $this->url_token     = $params['urlAccessToken'];
        $this->url_resource  = $params['urlResource'];

        $this->scope         = isset($params['scope']) ? $params['scope'] : ['email'];
        $this->response_type = 'code';
        $this->grant_type    = 'authorization_code';
    }

    public function getLoginUrl() {
        $this->scope = implode(',', $this->scope);
        return $this->url_auth . '?response_type=' . $this->response_type . '&scope=' . $this->scope . '&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri . '&state=' . $this->state;
    }

    public function getToken($code) {
        try {
            $this->code = $code;
            $accessTokenUri = $this->url_token . '?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&code=' . $this->code . '&grant_type=' . $this->grant_type . '&redirect_uri=' . $this->redirect_uri;
            $guzzleRequest = $this->guzzleClient->request('POST', $accessTokenUri);
            $this->token = $guzzleRequest->getBody()->getContents();
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
        return $this->token;
    }

    private function getAccessToken() {
        $token = json_decode($this->token, true);
        return $token['access_token'];
    }

    public function getResource() {
        try {
            $guzzleRequest = $this->guzzleClient->request('GET', $this->url_resource . '?access_token=' . $this->getAccessToken(), ['headers' => [ 'authorization: Bearer ' . $this->getAccessToken() ]]);
            $this->resource = $guzzleRequest->getBody()->getContents();
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
        return $this->resource;
    }

}