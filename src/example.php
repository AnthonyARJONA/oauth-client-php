<?php

require '../vendor/autoload.php';

use Mnt\OAuth\OAuth;

session_start();


$oauth_gl = new OAuth([
    'clientId'                => '',
    'clientSecret'            => '',
    'redirectUri'             => 'http://localhost:8081?client=google',
    'authorizationEndpoint'   => 'https://accounts.google.com/o/oauth2/v2/auth',
    'accessTokenEndpoint'     => "https://oauth2.googleapis.com/token",
    'userInfoEndpoint'        => "https://openidconnect.googleapis.com/v1/userinfo",
    'scode'                   => ['openid', 'email', 'profile'],
]);

$oauth_fb = new OAuth([
    'clientId'                => '',
    'clientSecret'            => '',
    'redirectUri'             => 'http://localhost:8081/?client=facebook',
    'authorizationEndpoint'   => 'https://www.facebook.com/v11.0/dialog/oauth',
    'accessTokenEndpoint'     => "https://graph.facebook.com/v11.0/oauth/access_token",
    'userInfoEndpoint'        => "https://graph.facebook.com/me?fields=id,name,email",
    'scode'                   => ['email', 'id', 'name'],
]);

$oauth_server = new OAuth([
    'clientId'                => '',
    'clientSecret'            => '',
    'redirectUri'             => 'http://localhost:8081/?client=server',
    'authorizationEndpoint'   => 'http://localhost:8082/auth',
    'accessTokenEndpoint'     => "http://oauth-server:8082/token",
    'userInfoEndpoint'        => "http://oauth-server:8082/me",
    'scode'                   => ['basic'],
]);

$oauth_dc = new OAuth([
    'clientId'                => '',
    'clientSecret'            => '',
    'redirectUri'             => 'http://localhost:8081/?client=discord',
    'authorizationEndpoint'   => 'https://discord.com/api/oauth2/authorize',
    'accessTokenEndpoint'     => "https://discord.com/api/oauth2/token",
    'userInfoEndpoint'        => "https://discord.com/api/oauth2/@me",
    'scode'                   => ['identify', 'email'],
]);


if(isset($_GET['code']) && isset($_GET['client'])) {

    switch ($_GET['client']) {
        case 'google':
            $accessToken = $oauth_gl->getToken($_GET['code']);
            $user = $oauth_gl->getResource();
            break;
        case 'facebook':
            $accessToken = $oauth_fb->getToken($_GET['code']);
            $user = $oauth_fb->getResource();
            break;
        case 'server':
            $accessToken = $oauth_server->getToken($_GET['code']);
            $user = $oauth_server->getResource();
            break;
        case 'discord':
            $accessToken = $oauth_dc->getToken($_GET['code']);
            $user = $oauth_dc->getResource();
            break;
    }

    var_dump($user);
    //logged

} else {
    echo "<a href=". $oauth_gl->getAuthUrl() .">Login with google</a><br>";
    echo "<a href=". $oauth_fb->getAuthUrl() .">Login with facebook</a><br>";
    echo "<a href=". $oauth_server->getAuthUrl() .">Login with server</a><br>";
    echo "<a href=". $oauth_dc->getAuthUrl() .">Login with discord</a>";
}
