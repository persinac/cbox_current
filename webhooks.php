<?php

$my_dir =  __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('2fq4dyy72z2bfzq4');
Braintree_Configuration::publicKey('y8f2fpcrcc9v5zmf');
Braintree_Configuration::privateKey('567d770926501e99b780c56ee45b1e91');

if(isset($_GET["bt_challenge"])) {
    echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
}

?>