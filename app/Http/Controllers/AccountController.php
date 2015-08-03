<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AccountController extends Controller {

	public function create()
	{
		return view('accounts.create');
	}

	public function store()
	{
		//
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
