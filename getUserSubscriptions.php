<?php 
$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$customer_id = isset($_POST['c_id']) ? $_POST['c_id'] : '000000';

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$customer = Braintree_Customer::find($customer_id);

$collection = $customer->creditCards;
$c_count = 0;

foreach($collection AS $item) {
	$collection2 = $item->subscriptions;
	foreach($collection2 AS $item2) {
		if($item2->status == "Active") {
			echo "<p id=\"sub_". $c_count . "\">ID: " . $item2->id . " Status: " . $item2->status . " Next Billing Date: " . $item2->nextBillingDate->format('Y-m-d') . " Next Billing Amount: " . $item2->nextBillingPeriodAmount ." <input type=\"hidden\" id=\"uni_sub_".$c_count."\" value=\"".$item2->id ."\"> <input type=\"button\" value=\"Cancel\" id=\"removebutton\" onclick=\"removeSubRow(".$c_count.");\"></p>";
		} else if($item2->status == "Pending") {
			echo "<p>ID: " . $item2->id . " Status: " . $item2->status . " Next Billing Date: " . $item2->nextBillingDate->format('Y-m-d') . " Next Billing Amount: " . $item2->nextBillingPeriodAmount . "</p>";
		} else {
			echo "<p>ID: " . $item2->id . " Status: " . $item2->status . "</p>";
		}
		$c_count = $c_count + 1;
	}
	
}

?>