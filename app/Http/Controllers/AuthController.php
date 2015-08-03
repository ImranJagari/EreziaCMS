<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use Auth;

class AuthController extends Controller {

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	public function auth()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$user = Account::where('Login', $request->input('username'))->first();

		if ($user && $user->PasswordHash == md5($request->input('password')))
		{
			Auth::login($user);
			return redirect('/');
		}
		else
		{
			return redirect()->back()->withErrors(['auth' => 'Nom de compte ou mot de passe incorrect.'])->withInput();
		}
	}

	public function logout()
	{
		if (Auth::check())
		{
			Auth::logout();
		}

		return redirect('/');
	}

}
