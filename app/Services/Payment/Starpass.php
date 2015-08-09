<?php

namespace App\Services\Payment;

use App\Services\Payment;

class Starpass extends Payment
{
    private $rates;

    const CACHE_EXPIRE_MINUTES = 1440; // 1 day

    public function __construct()
    {
        $json = null;

        if (Cache::has('payment.starpass'))
        {
            $json = Cache::get('payment.starpass');
        }
        else
        {
            $url = config('dofus.payment.starpass.url');
            $json = json_decode(file_get_contents($url));

            Cache::add('payment.starpass', $json, self::CACHE_EXPIRE_MINUTES);
        }

        $this->rates = new \stdClass;

        foreach ($json as $countryName => $country)
        {
            $this->rates->$countryName = new \stdClass;
            $palier = "one";

            foreach ($country as $methodName => $method)
            {
                $this->rates->$countryName->$methodName = new \stdClass;

                $newMethod = new \stdClass;
                $newMethod->devise = $method->sCurrencyToDisplay;
                $newMethod->points = 100;

                if ($methodName == "sms")
                {
                    $newMethod->number  = $method->smsPhoneNumber;
                    $newMethod->keyword = $method->smsKeyword;
                    $newMethod->cost    = $method->smsCostDetail;
                    $newMethod->text    = "{$method->smsCostDetail}/SMS + prix d'un SMS<br>1 envoi de SMS par code d'accès";
                }

                if ($methodName == "audiotel" || $methodName == "mobilecall")
                {
                    $newMethod->number = $method->audiotelPhone;
                    $newMethod->cost   = $method->audiotelFixedCostDetail;
                    $newMethod->text   = "{$method->audiotelFixedCostDetail}/appel {$method->audiotelVariableCostDetail}/min depuis une ligne fixe<br>Obtention du code en < 1,30 min. Coût : ".$method->fCostPerAction + (substr($method->audiotelVariableCostDetail, 2, 5) * 1.5)." {$method->sCurrencyToDisplay}";
                }

                $this->rates->$countryName->$methodName->$palier = $newMethod;
            }
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

        $key        = urlencode(config('dofus.payment.starpass.idp') . ";;" . config('dofus.payment.starpass.idd'));
        $validation = config('dofus.payment.starpass.validation');

        $validation = str_replace('{KEY}',  $key,  $validation);
        $validation = str_replace('{CODE}', $code, $validation);

        $check->provider = config('dofus.payment.starpass.name');

        $result = @file_get_contents($validation);
        $result = explode('|', $result);

        if ($result[0] == "OUI")
        {
            $check->success = true;
            $check->country     = $result[2];
            $check->palier_name = $result[3];
            $check->palier_id   = $result[4];
            $check->type        = $result[5];
            $check->points      = 100;
        }
        else
        {
            $check->message = "Code invalide.";
            $check->success = false;
        }

        return $check;
    }
}
