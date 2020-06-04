<?php

namespace OneSite\Napas\Billing;


/**
 * Class Service
 * @package OneSite\Napas\Billing
 */
class Service
{
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
        $this->apiUrl = Config::get('napas.billing.api_url');
        $this->userId = Config::get('napas.billing.user_id');
        $this->userPassword = Config::get('napas.billing.user_password');
        $this->npayPrivateKey = Config::get('napas.billing.9pay_private_key');
        $this->npayPublicKey = Config::get('napas.billing.9pay_public_key');
        $this->npayPassword = Config::get('napas.billing.9pay_password');
    }

    /**
     *
     */
    public function payment()
    {
        $params = [
            'mti' => '0200',
            'processingCode' => '050000',
            'amount' => 10000,
            'transDate' => date('YmdHis'),
            'trace' => time(),
            'channel' => 6012,
            'agentID' => 905001,
            'currencyCode' => 704,
            'serviceCode' => 'VTVCABBILL',
            'paymentID' => '6751958',
            // 'signature' => '',
        ];

        $text = md5(implode('', $params));

        $rsa = new RSA();

        //$params['signature'] = $this->sign($text);
        $params['signature'] = $rsa->sign(
            $this->npayPrivateKey,
            $text,
            $this->npayPassword
        );

        var_dump($text, $params['signature']);

        //var_dump($this->verify($params['signature'], $text));
        var_dump($rsa->verify(
            $this->npayPublicKey,
            $text,
            $params['signature']
        ));

        exit;
    }

    /**
     * @param $data
     * @return string
     */
    private function sign($data)
    {
        $privateKey = file_get_contents('/Sources/Packages/napas-billing/storage/credentials/1591263414_private.key');

        $privateKeyId = openssl_pkey_get_private($privateKey);

        openssl_sign($data, $binarySignature, $privateKeyId, OPENSSL_ALGO_SHA1);

        return base64_encode($binarySignature);
    }

    /**
     * @param $sign
     * @param $data
     * @return bool
     */
    private function verify($sign, $data)
    {
        $publicKey = file_get_contents('/Sources/Packages/napas-billing/storage/credentials/1591263414_public.pem');

        $publicKeyId = openssl_pkey_get_public($publicKey);

        return (bool)openssl_verify($data, base64_decode($sign), $publicKeyId, OPENSSL_ALGO_SHA1);
    }
}
