<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/3/21
 * Time: 下午1:03
 */

namespace app\libs;

use GuzzleHttp\Client;


class Exmail
{
    CONST TOKEN_URL = 'https://exmail.qq.com/cgi-bin/token';
    CONST USER_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/get';

    private $clientID;
    private $clientSecret;

    public function __construct()
    {
        $this->clientID = config('services.exmail.client_id');
        $this->clientSecret = config('services.exmail.client_secret');
    }

    public function login($name, $email)
    {
        $token = $this->getToken();

        return $this->exmailValidate($name, $email, $token);
    }

    public function getToken(){
        $response = $this->sendRequest('POST', self::TOKEN_URL, [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        return json_decode($response)->access_token;
    }

    public function sendRequest($method, $url, $query = [])
    {
        $client = new Client(['verify' => false]);

        return $client->request($method, $url, [
            'query' => $query
        ])->getBody()->getContents();
    }

    public function exmailValidate($name, $email, $token)
    {
        $response = $this->sendRequest('POST', self::USER_URL, [
            'alias' => $email,
            'access_token' => $token,
        ]);

        $response = json_decode($response);

        return (property_exists($response, 'Name') && $response->Name === $name)?true:false;
    }

}