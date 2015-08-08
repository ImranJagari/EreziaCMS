<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

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

    public function code(Request $request)
    {
        $country = $request->old('country');
        $method  = $request->old('method');
        $code    = $request->old('code');
        $cgv     = $request->old('cgv');

        $data = [];
        $data['country'] = (!empty($country) ? $country : $request->input('country'));
        $data['method']  = (!empty($method)  ? $method  : $request->input('method'));
        $data['code']    = (!empty($code)    ? $code    : $request->input('code'));
        $data['cgv']     = (!empty($cgv)     ? $cgv     : $request->input('cgv'));

        $split = explode('_', $data['method']);
        $data['method_'] = @$split[0];
        $data['palier']  = @$split[1];

        $validator = Validator::make($data, [
            'country' => 'required|size:2|alpha_num',
            'method_' => 'required|in:sms,audiotel,mobilecall',
            'cgv'     => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->route('shop.payment.method', $data['country'])->withErrors($validator);
        }

        if (!isset($this->payment->palier($data['country'], $data['method_'], $data['palier'])))
        {
            return redirect()->route('shop.payment.method', $data['country'])->withErrors(['palier' => 'Le palier selectionné est invalide.']);
        }
        else
        {
            $payment = $this->payment->palier($data['country'], $data['method_'], $data['palier']);
            $country = $data['country'];

            return view('shop.payment.code', [
                'payment' => $payment,
                'country' => $country,
                'method' => $data['method_'],
                'palier' => $data['palier'],
                'cgv' => 1
            ]);
        }
    }
}
