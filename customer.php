<?php

$my_dir =  __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('2fq4dyy72z2bfzq4');
Braintree_Configuration::publicKey('y8f2fpcrcc9v5zmf');
Braintree_Configuration::privateKey('567d770926501e99b780c56ee45b1e91');


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