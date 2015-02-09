<?php 
$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$customer_id = isset($_POST['c_id']) ? $_POST['c_id'] : '000000';
$optional = $_POST['optional'];
Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$customer = Braintree_Customer::find($customer_id);

$collection = $customer->creditCards;
$count = 0;

if($optional == "yes") {
	foreach($collection AS $item) {
		echo "<option value=\"".$item->token."\">Credit Card Ending in: " . $item->last4 . " Expires: ". $item->expirationDate . "</option>";
		$count = $count + 1;
	}
} else {
	foreach($collection AS $item) {
		echo "<p id=\"". $count . "\">Credit Card Ending in: " . $item->last4 . " Expires: ". $item->expirationDate . " <input type=\"hidden\" id=\"uni_".$count."\" value=\"".$item->token ."\"> <input type=\"button\" value=\"Remove\" id=\"removebutton\" onclick=\"removeRow(".$count.");\"></p>";
		$count = $count + 1;
	}
}

?>