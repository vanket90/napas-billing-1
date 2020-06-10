<?php

namespace OneSite\Napas\Billing;


use GuzzleHttp\Client;


/**
 * Class NapasBillingService
 * @package OneSite\Napas\Billing
 */
class NapasBillingService implements NapasBillingInterface
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
    /**
     * @var array|mixed|null
     */
    private $npayPrivateKey;
    /**
     * @var array|mixed|null
     */
    private $npayPublicKey;
    /**
     * @var array|mixed|null
     */
    private $npayPassword;

    /**
     * @var string
     */
    private $channel = '7299';

    /**
     * @var string
     */
    private $currencyCode = '704';

    /**
     * @var
     */
    private $transDate;
    /**
     * @var
     */
    private $trace;

    /**
     * @var string
     */
    private $agentID = '905029';

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

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return array|mixed|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array|mixed|null
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @return array|mixed|null
     */
    public function getNpayPrivateKey()
    {
        return $this->npayPrivateKey;
    }

    /**
     * @return array|mixed|null
     */
    public function getNpayPublicKey()
    {
        return $this->npayPublicKey;
    }

    /**
     * @return array|mixed|null
     */
    public function getNpayPassword()
    {
        return $this->npayPassword;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return mixed
     */
    public function getTransDate()
    {
        return date('YmdHis');
    }

    /**
     * @return mixed
     */
    public function getTrace()
    {
        return time();
    }

    /**
     * @return string
     */
    public function getAgentID(): string
    {
        return $this->agentID;
    }

    /**
     * @param $amount
     * @return string
     */
    public function getAmount($amount)
    {
        return str_pad($amount, 10, "0", STR_PAD_LEFT) . '00';
    }

    /**
     * @param $params
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInfo($params)
    {
        $amount = !empty($params['amount']) ? $params['amount'] : 0;
        $serviceCode = !empty($params['service_code']) ? $params['service_code'] : '';
        $paymentId = !empty($params['payment_id']) ? $params['payment_id'] : '';

        $params = [
            'mti' => '0200',
            'processingCode' => '050000',
            'amount' => $this->getAmount($amount),
            'transDate' => $this->getTransDate(),
            'trace' => $this->getTrace(),
            'channel' => $this->getChannel(),
            'agentID' => $this->getAgentID(),
            'currencyCode' => $this->getCurrencyCode(),
            'serviceCode' => $serviceCode,
            'paymentID' => $paymentId,
        ];

        $params['signature'] = $this->getSignature($params);

        $client = new Client();
        $response = $client->request('POST', "https://vasagency-sandbox.napas.com.vn:47666/agentService/payments/", [
            'http_errors' => false,
            'verify' => false,
            'headers' => $this->getHeaders(),
            'body' => json_encode($params)
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode != 200) {
            return [
                'error' => [
                    'message' => 'Có lỗi xảy ra. Vui lòng thử lại.',
                    'status_code' => $statusCode
                ]
            ];
        }

        return [
            'data' => json_encode($response->getBody()->getContents())
        ];
    }

    /**
     * @param $params
     * @return mixed|void
     */
    public function payment($params)
    {
        // TODO: Implement payment() method.
    }

    /**
     * @param $paymentId
     * @return mixed|void
     */
    public function paymentInfo($paymentId)
    {
        // TODO: Implement paymentInfo() method.
    }

    /**
     * @return array
     */
    private function getHeaders()
    {
        return [
            'Authorization' => "Basic " . base64_encode($this->getUserId() . ":" . $this->getUserPassword()),
            "Content-Type" => "application/json"
        ];
    }

    /**
     * @param array $params
     * @return string
     */
    private function getSignature(array $params)
    {
        $data = implode('', $params);

        $privateKey = file_get_contents(storage_path('credentials/9pay_napas_billing_private.key'));

        $privateKeyId = openssl_pkey_get_private($privateKey);

        openssl_sign($data, $binarySignature, $privateKeyId, OPENSSL_ALGO_MD5);

        return base64_encode($binarySignature);
    }
}
