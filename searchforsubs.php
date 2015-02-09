<?php

$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$sub_id_to_search = "";

$collection = Braintree_Subscription::search(array(
  Braintree_SubscriptionSearch::status()->in(
    array(Braintree_Subscription::ACTIVE)
  )
));
echo "<h3>All Subs</h3>";
foreach($collection AS $item) {
	echo "<p>Subscription Item = " . $item->id . "</p>";
	$sub_id_to_search = $item->id;
}
echo "<h3>Subscription Search</h3>";
$subscription = Braintree_Subscription::find($sub_id_to_search);
echo $subscription;

echo "<h3>Customer Search</h3>";
$customers = Braintree_Customer::all();
foreach($customers AS $item) {
	echo "<p>Customer ID = " . $item->id . "</p>";
	$collection = Braintree_Customer::find( $item->id );
}
/**************** FIELDS ********************

addOns=, balance=0.00, billingDayOfMonth=16, billingPeriodEndDate=, billingPeriodStartDate=,
 currentBillingCycle=0, daysPastDue=, discounts=, failureCount=0, firstBillingDate=Monday, 
 16-Jun-14 00:00:00 EDT, id=4bjvvg, merchantAccountId=75d44dzsgs87pjqk, neverExpires=1, 
 nextBillAmount=9.99, nextBillingPeriodAmount=9.99, nextBillingDate=Monday, 16-Jun-14 00:00:00 EDT, 
 numberOfBillingCycles=, paidThroughDate=, paymentMethodToken=8zc38m, planId=test_plan_1,
 price=9.99, status=Active, trialDuration=14, trialDurationUnit=day,
 trialPeriod=1, descriptor=Braintree_Descriptor[name=, phone=], transactions=]


*************************************/


echo "<h3>All Subs including trials</h3>";
$collection = Braintree_Subscription::search(array(
  Braintree_SubscriptionSearch::status()->in(
    array(Braintree_Subscription::ACTIVE)
  ),
  Braintree_SubscriptionSearch::inTrialPeriod()->is(true)
));

echo "<h3>All Subs excluding trials</h3>";
$collection = Braintree_Subscription::search(array(
  Braintree_SubscriptionSearch::status()->in(
    array(Braintree_Subscription::ACTIVE)
  ),
  Braintree_SubscriptionSearch::inTrialPeriod()->is(false)
));

?>