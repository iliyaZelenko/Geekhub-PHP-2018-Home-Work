<?php

namespace App;

require '../vendor/autoload.php';
use Pecee\SimpleRouter\SimpleRouter as Router;
use IlyaZelen\UserOauth2Services\OauthProvidersManager;

header('Access-Control-Allow-Origin: *'); // CORS
header('Content-Type: application/json'); // REST API возвращает JSON

OauthProvidersManager::init([
    [
        'name' => 'google',
        'clientId' => '418799322900-hk88fkv35a3igd2hi6qibo2ej64p9kua.apps.googleusercontent.com',
        'clientSecret' => 'aNW5GbaBDoipDITEzn67aOeG',
        'redirectUrl' => 'http://localhost:8080/oauth/redirect/google'
    ],
    [
        'name' => 'github',
        'clientId' => '6351d734c1841cc6b7e6',
        'clientSecret' => '4307fc4127bc23309daff47f84517d6a58d5a72b',
        'redirectUrl' => 'http://localhost:8080/oauth/redirect/github'
    ],
    [
        'name' => 'facebook',
        'clientId' => '1877646002278860',
        'clientSecret' => 'fc7c4c93573912a66bb3c7e0fd3cc36f',
        'redirectUrl' => 'http://localhost:8080/oauth/redirect/facebook'
    ]
], [
    'guzzleOptions' => [
        'timeout' => 3.14
    ]
]);

// две скобки чтобы не было экранирования
Router::setDefaultNamespace("\App\Controllers");


Router::get('/oauth/get-auth-url/{provider}', 'AuthController@url');
Router::get('/oauth/get-user/{provider}', 'AuthController@user');
Router::get('/oauth/get-user-by-token', 'AuthController@userByToken');

// Start the routing
Router::start();
