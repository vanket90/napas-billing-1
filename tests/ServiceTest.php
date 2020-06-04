<?php

namespace OneSite\Napas\Billing;


use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{

    /**
     * @var Service
     */
    private $service;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new Service();
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
     * PHPUnit test: vendor/bin/phpunit --filter testPayment tests/ServiceTest.php
     */
    public function testPayment()
    {
        $data = $this->service->payment();

        echo "\n" . json_encode($data);

        return $this->assertTrue(true);
    }

}
