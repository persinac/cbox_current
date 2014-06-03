<?php 
$my_dir =  __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/";

/*
$dir    = __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/Braintree/";
$files1 = scandir($dir);

print_r($files1);
*/

require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('2fq4dyy72z2bfzq4');
Braintree_Configuration::publicKey('y8f2fpcrcc9v5zmf');
Braintree_Configuration::privateKey('567d770926501e99b780c56ee45b1e91');


$result = Braintree_Transaction::sale(array(
    "amount" => "1000.00",
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
