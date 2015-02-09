<?php

$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');


$result = Braintree_Customer::create(array(
    "firstName" => $_POST["first_name"],
    "lastName" => $_POST["last_name"],
    "creditCard" => array(
        "number" => $_POST["number"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"],
        "cvv" => $_POST["cvv"],
        "billingAddress" => array(
            "postalCode" => $_POST["postal_code"]
        )
    )
));

if ($result->success) {
    echo("Success! Customer ID: " . $result->customer->id);
	//insert into box_customer table: first_name, last_name, c_id
	echo("<a href='./subscription.php?customer_id=" . $result->customer->id . "'>Create subscription for this customer</a>");
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}

?>