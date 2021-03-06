<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Account extends Model implements AuthenticatableContract {

	use Authenticatable;

	protected $primaryKey = 'Id';

	protected $fillable = array(
		'Login',
		'PasswordHash',
		'Nickname',
		'Role',
		'Ticket',
		'SecretQuestion',
		'SecretAnswer',
		'Lang',
		'Email',
		'CreationDate',
		'SubscriptionEnd',
		'LastVote',
		'VoteCount',
	);

	protected $table = 'accounts';

	protected $connection = 'auth';

	public $timestamps = false;

	protected $hidden = array('PasswordHash');

	public static $rules = array(
		'username'             => 'required|min:3|max:32|unique:accounts,Login|alpha_num',
		'password'             => 'required|min:6',
		'password_confirm' 	   => 'required|same:password',
		'email'                => 'required|email|unique:accounts,Email',
		'g-recaptcha-response' => 'required|recaptcha',
		'cg'                   => 'required'
	);

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->PasswordHash;
	}

	public function getRememberToken()
	{
		return null; // not supported
	}

	public function setRememberToken($value)
	{
		// not supported
	}

	public function getRememberTokenName()
	{
		return null; // not supported
	}

	public function isAdmin()
	{
		if ($this->Role >= 4)
			return true;
		else
			return false;
	}

	public function isStaff()
	{
		if ($this->Role > 1)
			return true;
		else
			return false;
	}

	public function transactions()
	{
		return $this->hasMany('App\Transaction', 'account', 'Id')->orderBy('date', 'desc');
	}
}
