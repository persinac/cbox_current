<?php 

$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$c_id = $_POST['customer_id'];

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$exp_date =  $_POST['cc_exp_month'] .'/'. $_POST['cc_exp_year'];

$result = Braintree_CreditCard::create(array(
	'customerId' => $c_id, 
	'cardholderName' => $_POST['cc_name'],
	'number' => $_POST['cc_num'],
	'expirationDate' => $exp_date,
	'cvv' => $_POST['cc_cvv'],
	'billingAddress' => array(
        'firstName' => $_POST['cc_bill_fname'],
        'lastName' => $_POST['cc_bill_lname'],
        'streetAddress' => $_POST['cc_bill_address'],
        'extendedAddress' => $_POST['cc_bill_ext_address'],
        'locality' => $_POST['cc_bill_city'],
        'region' => $_POST['cc_bill_state'],
        'postalCode' => $_POST['cc_bill_zip'],
        'countryCodeAlpha2' => $_POST['cc_bill_country']
	)
));
if ($result->success) {
	echo($result->creditCard->token);
	//insert into box_customer table: first_name, last_name, c_id
} else {
	echo("Validation errors:<br/>");
	foreach (($result->errors->deepAll()) as $error) {
		echo("- " . $error->message . "<br/>");
	}
}


?>