# OAuth Client

## Requirements

- [GuzzleHTTP](https://github.com/guzzle/guzzle)
- [PHP](https://www.php.net/) >= 7.3

## Installation

Download and install Composer by following the [official instructions](https://getcomposer.org/download/).

For usage, [see the documentation](https://getcomposer.org/doc/).

Run this command to install the package with composer

```bash
composer require mimso/oauth-client-php
```

## Usage

Example for google oauth API.

```bash
$oauth = new \Mnt\OAuth\OAuth([
    'clientId'                => '0000',
    'clientSecret'            => '0000',
    'redirectUri'             => 'https://my.url/',
    'authEndpoint'            => 'https://accounts.google.com/o/oauth2/v2/auth',
    'accessTokenEndpoint'     => 'https://oauth2.googleapis.com/token',
    'userInfoEndpoint'        => 'https://openidconnect.googleapis.com/v1/userinfo',
    'scope'                   => ['openid', 'email', 'profile'],
]);

if(isset($_GET['code'])) {
    $accessToken = $oauth->getToken($_GET['code']);
    $user = $oauth->getResource();
    var_dump($user) //logged
} else {
    echo "<a href=". $oauth->getAuthUrl() .">login with google</a>";
}
```

## License

oauth-client-php is available under [MIT LICENSE](https://github.com/Mimso/oauth-client-php/blob/main/LICENSE).
