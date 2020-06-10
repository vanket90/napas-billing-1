<?php


namespace OneSite\Napas\Billing;


/**
 * Interface NapasBillingInterface
 * @package OneSite\Napas\Billing
 */
interface NapasBillingInterface
{
    /**
     * @param $params
     * @return mixed
     */
    public function getInfo($params);

    /**
     * @param $params
     * @return mixed
     */
    public function payment($params);

    /**
     * @param $paymentId
     * @return mixed
     */
    public function paymentInfo($paymentId);
}
