<?php

namespace OneSite\Napas\Billing;


use PHPUnit\Framework\TestCase;

/**
 * Class NapasBillingServiceTest
 * @package OneSite\Napas\Billing
 * vendor/bin/phpunit --filter testFunction tests/NapasBillingServiceTest.php
 */
class NapasBillingServiceTest extends TestCase
{

    /**
     * @var NapasBillingService
     */
    private $service;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new NapasBillingService();
    }

    /**
     *
     */
    public function tearDown(): void
    {
        $this->service = null;

        parent::tearDown();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetInfo()
    {
        $data = $this->service->getInfo([
            'amount' => 20000,
            'service_code' => 'VJABILLING',
            'payment_id' => '53983008',
        ]);

        echo "\n" . json_encode($data);

        return $this->assertTrue(true);
    }

    /**
     *
     */
    public function testPayment()
    {
        $data = $this->service->payment([
            'amount' => 20000,
            'service_code' => 'VJABILLING',
            'payment_id' => '53983008',
        ]);

        echo "\n" . json_encode($data);

        return $this->assertTrue(true);
    }

    /**
     *
     */
    public function testPaymentInfo()
    {
        $data = $this->service->getPaymentInfo('3133524');

        echo "\n" . json_encode($data);

        return $this->assertTrue(true);
    }

}
