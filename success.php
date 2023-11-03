<?php
echo "<pre>";
print_r($_GET);

$session_id = $_GET['payment_intent'];
// Include the Stripe PHP library 
require_once 'vendor/stripe/stripe-php/init.php';
 
// Set API key 
$stripe = new \Stripe\StripeClient('sk_test_NTFiLROEmc6LRRAXubw9pNFY00Gk3v0bQi'); 
 
// Fetch the Checkout Session to display the JSON result on the success page 
try { 

	$res = $stripe->paymentIntents->retrieve(
			  $session_id,
			  []
			);

    // $checkout_session = $stripe->checkout->sessions->retrieve($session_id); 
    echo('<pre>');
    print_r($res);
} catch(Exception $e) {  
    $api_error = $e->getMessage();  
    print_r($api_error);
}

die();


// Check whether stripe checkout session is not empty 
if(!empty($_GET['session_id'])){ 
}
?>