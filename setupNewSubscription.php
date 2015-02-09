<?php 
$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$customer_id = isset($_POST['c_id']) ? $_POST['c_id'] : '000000';

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$result = Braintree_Transaction::sale(array(
  'amount' => '2.00',
  'customerId' => $customer_id,
  'paymentMethodToken' => $_POST['cc_selector'],
  'options' => array(
    'submitForSettlement' => true
  )
));

if ($result->success) {

	$t_id = $result->transaction->id ;
	$result = Braintree_Subscription::create(array(
	  'paymentMethodToken' => $_POST['cc_selector'],
	  'planId' => 'monthly_sub',
	  'price' => $_POST['sub_total_monthly']
	));
	//echo("Size of transaction: " . sizeof($result->subscription->transactions)." New status of subscription: " . $result->subscription->status);
	if ($result->success) {
			echo("Transaction ID for startup fee: ".$t_id." <br/>Subscription ID " . $result->subscription->id . " is " . $result->subscription->status);
		} else {
			echo("Validation errors:<br/>");
			foreach (($result->errors->deepAll()) as $error) {
				echo("- " . $error->message . "<br/>");
			}
		}
	
} else if ($result->transaction) {
    echo("Error: " . $result->message);
    echo("<br/>");
    echo("Code: " . $result->transaction->processorResponseCode);
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}


?>