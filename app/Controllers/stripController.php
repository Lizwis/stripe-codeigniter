<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Stripe;

class StripController extends BaseController
{
	public function __construct()
	{
		helper(["url"]);
	}

	public function index()
	{
		return view('strip');
	}

	public function post_payment()
	{
		Stripe\Stripe::setApiKey(STRIPE_SECRET);

		$stripe = Stripe\Charge::create([
			"amount" => 1 * 100,
			"currency" => "usd",
			"source" => $_REQUEST["stripeToken"],
			"description" => "Test payment for ABC item",
		]);

		session()->setFlashdata("message", "Payment done successfully");

		return view('strip');
	}
}
