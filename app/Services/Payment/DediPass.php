<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;

class DediPass extends Payment
{
    private $rates;

    const CACHE_EXPIRE_MINUTES = 1440; // 1 day

    public function __construct()
    {
        $json = null;

        if (Cache::has('payment.dedipass'))
        {
            $json = Cache::get('payment.dedipass');
        }
        else
        {
            $url = config('dofus.payment.dedipass.url');
            $url = str_replace('{KEY}', config('dofus.payment.dedipass.key'), $url);
            $json = json_decode(file_get_contents($url));

            Cache::add('payment.dedipass', $json, self::CACHE_EXPIRE_MINUTES);
        }

        $this->rates = new \stdClass;

        foreach ($json as $method)
        {
            $countryName = strtolower($method->country->iso);
            $methodName  = strtolower($method->solution);
            $palier      = $method->rate;

            if (!property_exists($this->rates, $countryName))
            {
                $this->rates->$countryName = new \stdClass;
            }

            if (!property_exists($this->rates->$countryName, $methodName))
            {
                $this->rates->$countryName->$methodName = new \stdClass;
            }

            $newMethod = new \stdClass;

            $newMethod->devise = $method->user_currency == "EUR" ? "&euro;" : $method->user_currency;
            $newMethod->text   = $method->mention;
            $newMethod->cost   = $method->user_price . " " . $newMethod->devise;
            $newMethod->points = $method->user_earns;

            if ($methodName == "sms")
            {
                $newMethod->number  = $method->shortcode;
                $newMethod->keyword = $method->keyword;
            }

            if ($methodName == "audiotel" )
            {
                $newMethod->number = $method->phone;
            }

            $this->rates->$countryName->$methodName->$palier = $newMethod;
        }
    }

    public function rates()
    {
        return $this->rates;
    }

    public function palier($country, $method, $palier)
    {
        return $this->rates->$country->$method->$palier;
    }

    public function check($palier, $code)
    {
        $check = new \stdClass;
        $check->code = $code;
        $check->error = false;

        $key        = config('dofus.payment.dedipass.key');
        $validation = config('dofus.payment.dedipass.validation');

        $validation = str_replace('{KEY}', $key, $validation);
        $validation = str_replace('{PALIER}', $palier, $validation);
        $validation = str_replace('{CODE}', $code, $validation);

        $result = @file_get_contents($validation);

        $check->provider = config('dofus.payment.dedipass.name');

        $result = json_decode($result);

        if ($result->status == "success")
        {
            $check->success = true;

            $identifier = explode('-', $result->identifier);

            $check->country     = strtolower($identifier[1]);
            $check->palier_name = $result->identifier;
            $check->palier_id   = 0;
            $check->type        = strtolower($identifier[2]);
            $check->points      = $identifier[3];
        }
        else
        {
            $check->message = $result->message;
            $check->success = false;
        }

        return $check;
    }
}
