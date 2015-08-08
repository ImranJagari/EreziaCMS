<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Payment;
use App\Services\Payment\DediPass;
use App\Services\Payment\OneoPay;
use App\Services\Payment\Starpass;

class PaymentController extends Controller
{
    private $payment;

    public function __construct()
    {
        $used = config('dofus.payment.used');

        if ($used == "dedipass") $this->payment = new DediPass;
        if ($used == "oneopay")  $this->payment = new OneoPay;
        if ($used == "starpass") $this->payment = new Starpass;

        if (!$this->payment) die("No valid payment method found");
    }

    public function country()
    {
        return view('shop.payment.country', ['rates' => $this->payment->rates()]);
    }

    public function method($country = 'fr')
    {
        if (isset($this->payment->$country))
        {
            $methods = $this->payment->rates()->$country;
        }
        else
        {
            $methods = $this->payment->rates()->fr;
            $country = 'fr';
        }

        return view('shop.payment.method', ['methods' => $methods, 'country' => $country]);
    }

    public function code()
    {

    }
}
