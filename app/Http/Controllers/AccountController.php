<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App;
use Auth;
use Validator;

class AccountController extends Controller {

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $verifier = App::make('validation.presence');
        $verifier->setConnection('auth');
        $validator = Validator::make($request->all(), Account::$rules);
        $validator->setPresenceVerifier($verifier);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $account = new Account;
        $account->Login           = $request->input('username');
        $account->PasswordHash    = md5($request->input('password'));
        $account->Nickname        = $request->input('username');
        $account->Role            = 1;
        $account->Ticket          = $this->GenerateTicket();
        $account->SecretQuestion  = '2 + 2';
        $account->SecretAnswer    = '4';
        $account->Lang            = 'fr';
        $account->Email           = $request->input('email');
        $account->CreationDate    = date('Y-m-d H:i:s');
        $account->SubscriptionEnd = '2016-01-01 00:00:00';

        $account->save();

        Auth::login($account);

        return redirect()->route('home');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    private function GenerateTicket()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($chars);
        $ticket = '';
        for ($i = 0; $i < 32; $i++)
        {
            $ticket .= $chars[rand(0, $len - 1)];
        }
        return $ticket;
    }

}
