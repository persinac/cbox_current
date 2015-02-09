<?php 
$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";

/*
$dir    = __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/Braintree/";
$files1 = scandir($dir);

print_r($files1);
*/

require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');


$result = Braintree_Transaction::sale(array(
    "amount" => "1.00",
    "creditCard" => array(
        "number" => $_POST["number"],
        "cvv" => $_POST["cvv"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"]
    ),
    "options" => array(
        "submitForSettlement" => true
    )
));

if ($result->success) {
    echo("Success! Transaction ID: " . $result->transaction->id);
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
