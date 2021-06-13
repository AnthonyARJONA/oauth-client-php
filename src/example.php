<?php

require '../vendor/autoload.php';

use Mnt\OAuth\OAuth;

session_start();

$oauth = new OAuth([
    'clientId'                => '0000',
    'clientSecret'            => '0000',
    'redirectUri'             => 'http://',
    'urlAuthorize'            => 'https://accounts.google.com/o/oauth2/v2/auth',
    'urlAccessToken'          => 'https://oauth2.googleapis.com/token',
    'urlResource'             => 'https://www.googleapis.com/oauth2/v3/userinfo',
    'scope'                   => ['profile'],
]);

if(isset($_GET['code'])) {

    $accessToken = $oauth->getToken($_GET['code']);
    $user = $oauth->getResource();

    //logged

} else {
    echo "<a href=". $oauth->getLoginUrl() .">login with google</a>";
}
