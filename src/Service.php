<?php

namespace OneSite\Napas\Billing;


/**
 * Class Service
 * @package OneSite\Napas\Billing
 */
class Service
{
    public function payment()
    {
        return Config::get('napas.billing');
    }
}
