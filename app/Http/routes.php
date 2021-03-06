<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$locale = Request::segment(1);

if (in_array($locale, Config::get('app.locales')))
{
	App::setLocale($locale);
}
else
{
	$locale = null;
}

$router->group(['prefix' => $locale], function() {

	Route::any('/', [
		'uses' => 'PostController@index',
		'as' => 'home'
	]);

	/* NEWS */

	Route::get(Lang::get('routes.posts.index'), [
		'uses' => 'PostController@index',
		'as'   => 'posts'
	]);
	Route::get(Lang::get('routes.posts.show'), [
		'uses' => 'PostController@show',
		'as'   => 'posts.show'
	]);

	/* ACCOUNTS */

	//Route::resource('accounts', 'AccountController'); // NEED ONLY ARRAY

	Route::get(Lang::get('routes.account.register'), [
		'middleware' => 'guest',
		'uses' => 'AccountController@create',
		'as'   => 'register'
	]);

	Route::post(Lang::get('routes.account.register'), [
		'middleware' => 'guest',
		'uses' => 'AccountController@store',
		'as'   => 'register'
	]);

	Route::get(Lang::get('routes.account.dashboard'), [
		'middleware' => 'auth',
		'uses' => 'AccountController@dashboard',
		'as'   => 'dashboard'
	]);

	/* AUTH */

	Route::get(Lang::get('routes.account.login'), [
		'uses' => 'AuthController@auth',
		'as'   => 'login'
	]);
	Route::post(Lang::get('routes.account.login'), [
		'uses' => 'AuthController@login',
		'as'   => 'login'
	]);
	Route::get(Lang::get('routes.account.logout'), [
		'uses' => 'AuthController@logout',
		'as'   => 'logout'
	]);

	/* SHOP */

	Route::get(Lang::get('routes.shop.payment.choose-country'), [
		'middleware' => 'auth',
		'uses'   => 'PaymentController@country',
		'as'     => 'shop.payment.country'
	]);
	Route::get(Lang::get('routes.shop.payment.choose-method'), [
		'middleware' => 'auth',
		'uses'   => 'PaymentController@method',
		'as'     => 'shop.payment.method'
	]);
	Route::any(Lang::get('routes.shop.payment.get-code'), [
		'middleware' => 'auth',
		'uses'   => 'PaymentController@code',
		'as'     => 'shop.payment.code'
	]);
	Route::post(Lang::get('routes.shop.payment.process'), [
		'middleware' => 'auth',
		'uses'   => 'PaymentController@process',
		'as'     => 'shop.payment.process'
	]);

	/* VOTE */

	Route::get(Lang::get('routes.vote.index'), [
		'uses'   => 'VoteController@index',
		'as'     => 'vote.index'
	]);
	Route::get(Lang::get('routes.vote.process'), [
		'middleware' => 'auth',
		'uses'   => 'VoteController@process',
		'as'     => 'vote.process'
	]);
	Route::get(Lang::get('routes.vote.palier'), [
		'middleware' => 'auth',
		'uses'   => 'VoteController@palier',
		'as'     => 'vote.palier'
	]);
	Route::get(Lang::get('routes.vote.object'), [
		'middleware' => 'auth',
		'uses'   => 'VoteController@object',
		'as'     => 'vote.object'
	]);

	/* EVENTS */

	Route::get(Lang::get('routes.event.st-patrick'), [
		'middleware' => 'auth',
		'uses'   => 'EventController@st_patrick',
		'as'     => 'event.st-patrick'
	]);

	/* PAYPAL */

	Route::get('paypal', [
		'middleware' => 'auth',
		'uses'	=> 'PaypalController@index',
		'as'	=> 'paypal'
	]);

	Route::get('paypal/success', [
		'middleware' => 'auth',
		'uses'	=> 'PaypalController@success',
		'as'	=> 'paypal.success'
	]);

	Route::get('paypal/cancel', [
		'middleware' => 'auth',
		'uses'	=> 'PaypalController@cancel',
		'as'	=> 'paypal.cancel'
	]);

});

/* API */

Route::get('api/forge/{request}', 'Api\ForgeController@forge')->where('request', '(.*)');
Route::get('api/text/{id}', 'Api\ForgeController@text')->where('id', '[0-9]+');

Route::post('game/authentification.json', 'Api\AccountController@auth');
Route::post('game/account.json', 'Api\AccountController@info');
Route::post('game/shop.json', 'Api\ShopController@shop');
