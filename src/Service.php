<?php

namespace OneSite\Napas\Billing;


use GuzzleHttp\Client;

/**
 * Class Service
 * @package OneSite\Napas\Billing
 */
class Service
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array|mixed|null
     */
    private $apiUrl;
    /**
     * @var array|mixed|null
     */
    private $userId;
    /**
     * @var array|mixed|null
     */
    private $userPassword;
    private $npayPrivateKey;
    private $npayPublicKey;
    private $npayPassword;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->client = new Client();

        $this->apiUrl = config('napas.billing.api_url');
        $this->userId = config('napas.billing.user_id');
        $this->userPassword = config('napas.billing.user_password');
        $this->npayPrivateKey = config('napas.billing.9pay_private_key');
        $this->npayPublicKey = config('napas.billing.9pay_public_key');
        $this->npayPassword = config('napas.billing.9pay_password');
    }

    /**
     * @return array|mixed|null
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function payment()
    {
        $params = [
            'mti' => '0200',
            'processingCode' => '050000',
            'amount' => 10000,
            'transDate' => date('YmdHis'),
            'trace' => '200605001',
            'channel' => 6012,
            'agentID' => 905001,
            'currencyCode' => 704,
            'serviceCode' => 'VTVCABBILL',
            'paymentID' => '6751958'
        ];

        $text = md5(implode('', $params));

        $rsa = new RSA();

        $params['signature'] = $rsa->sign(
            $this->npayPrivateKey,
            $text,
            $this->npayPassword
        );

        $response = $this->client->request('POST', $this->getApiUrl() . "/payments", [
            'http_errors' => false,
            'verify' => false,
            'form_params' => $params
        ]);

        var_dump($response);
        exit;
    }

}
