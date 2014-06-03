<?php

$my_dir =  __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('2fq4dyy72z2bfzq4');
Braintree_Configuration::publicKey('y8f2fpcrcc9v5zmf');
Braintree_Configuration::privateKey('567d770926501e99b780c56ee45b1e91');

try {
	//get customer id from DB, NOT the URL
    $customer_id = $_GET["customer_id"];
    $customer = Braintree_Customer::find($customer_id);
    $payment_method_token = $customer->creditCards[0]->token;
	
	//$num_of_members = $_GET["num_of_members"];
	//$price_for_gym = $num_of_members * 2;
	
	//Start up fee
	$result = Braintree_Transaction::sale(array(
		"amount" => "5.00",
		"customer_id" => $customer_id,
		"paymentMethodToken" => $payment_method_token,
		"options" => array(
			"submitForSettlement" => true
		)
	));
	if ($result->success) {
		$result = Braintree_Subscription::create(array(
			'paymentMethodToken' => $payment_method_token,
			'planId' => 'test_plan_1',
			'price' => '0.00'
		));

		if ($result->success) {
			echo("Success! Subscription " . $result->subscription->id . " is " . $result->subscription->status);
		} else {
			echo("Validation errors:<br/>");
			foreach (($result->errors->deepAll()) as $error) {
				echo("- " . $error->message . "<br/>");
			}
		}
	}
	else {
		echo("Validation errors:<br/>");
		foreach (($result->errors->deepAll()) as $error) {
			echo("- " . $error->message . "<br/>");
		}
	}
} catch (Braintree_Exception_NotFound $e) {
    echo("Failure: no customer found with ID " . $_GET["customer_id"]);
}

?>