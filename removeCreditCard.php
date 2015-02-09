<?php 
$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$token = isset($_POST['cc_token']) ? $_POST['cc_token'] : '000000';

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$result = Braintree_CreditCard::delete($token);

?>