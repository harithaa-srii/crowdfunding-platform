<?php

require('stripe-php-master/init.php');

$publishableKey = "pk_test_51OvZjUSIFBYFZpizQsMAJJq0YE1JBK73MQIDST8dNMWiyIAY0LWadhAIJfIjZxr3VcuhI9k2b6GImdp7QGEhgVrJ00pHbFXjei";
$secretKey = "sk_test_51OvZjUSIFBYFZpizj0Woos6MQl6zMI0bLPSmflagftM023dB99iAlWBX66bqVtS97REps1rG0BKIDLSpGORAvuD900XLk6QC7Z";

\Stripe\Stripe::setApiKey($secretKey);

?>
