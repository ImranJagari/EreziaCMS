<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Route;
use Request;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;

class PaypalController extends Controller {

	private $apiContext;

	public function __construct()
	{
		$this->apiContext = new ApiContext(
			new OAuthTokenCredential(
				env('PAYPAL_CLIENT_ID', ''),
				env('PAYPAL_SECRET')
			)
		);
	}

	public function index()
	{
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$item1 = new Item();
		$item1->setName("500 Ogrines")
			->setCurrency("EUR")
			->setQuantity(1)
			->setPrice(48.00);

		$itemList = new ItemList();
		$itemList->setItems([$item1]);

		$details = new Details();
		$details->setShipping(0)
			->setTax(0.00)
			->setSubtotal(48.00);

		$amount = new Amount();
		$amount->setCurrency("EUR")
			->setTotal(48.00)
			->setDetails($details);

		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setDescription("Achat d'Orgines sur le serveur Erezia");

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl(route('paypal.success'))
			->setCancelUrl(route('paypal.cancel'));

		$payment = new Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions([$transaction]);

		try
		{
			$payment->create($this->apiContext);
		}
		catch (PayPal\Exception\PPConnectionException $e)
		{
			echo "Exception: " . $e->getMessage() . PHP_EOL;
			var_dump($e->getData());
			exit(1);
		}

		foreach ($payment->getLinks() as $link)
		{
			if($link->getRel() == 'approval_url')
			{
				$redirectUrl = $link->getHref();
				break;
			}
		}

		$payment_id = $payment->getId();

		if (isset($redirectUrl))
		{
			return redirect($redirectUrl);
		}
		else
		{
			die("PayPal error");
		}
	}

	public function success()
	{
		$payment = Payment::get(Request::input('paymentId'), $this->apiContext);

		$execution = new PaymentExecution();
		$execution->setPayerId(Request::input('PayerID'));

		$result = $payment->execute($execution, $this->apiContext);

		dd($result);
	}

	public function cancel()
	{
		die("cancel");
	}

}
