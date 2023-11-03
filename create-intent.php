<?php
// Include the Stripe PHP library 
require_once 'vendor/stripe/stripe-php/init.php'; 

$stripe = new \Stripe\StripeClient("sk_test_NTFiLROEmc6LRRAXubw9pNFY00Gk3v0bQi");
	$intent = $stripe->paymentIntents->create(
	[
	'amount' => 1099,
	'currency' => 'usd',
	// In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
	'automatic_payment_methods' => ['enabled' => true],
	]
	);
	echo json_encode(array('client_secret' => $intent->client_secret));
?>