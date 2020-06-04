<?php


namespace OneSite\Napas\Billing;


/**
 * Class Config
 * @package OneSite\Napas\Billing
 */
class Config
{
    /**
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function get($key, $default = null)
    {
        if (!function_exists('config')) {
            return $default;
        }

        return config($key, $default);
    }
}
