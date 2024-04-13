<?php
require('config.php');

if(isset($_POST['stripeToken'])){
    // Initialize Stripe with your API key
    \Stripe\Stripe::setApiKey('sk_test_51OvZjUSIFBYFZpizj0Woos6MQl6zMI0bLPSmflagftM023dB99iAlWBX66bqVtS97REps1rG0BKIDLSpGORAvuD900XLk6QC7Z');

    // Get the token from the POST data
    $token = $_POST['stripeToken'];

    try {
        // Create a customer and attach the source to the customer
        $customer = \Stripe\Customer::create(array(
            "source" => $token,
        ));

        // Create a PaymentIntent with the customer's default payment method
        $paymentIntent = \Stripe\PaymentIntent::create(array(
            "amount" => 500, // Amount in the smallest currency unit (e.g., 500 cents for INR)
            "currency" => "inr",
            "description" => "Crowdfunding Platform desc", 
            "customer" => $customer->id, // Use the customer ID as the payment method
        ));

        // Output the PaymentIntent details
        echo "<pre>";
        print_r($paymentIntent);
    } catch (Exception $e) {
        // Handle any errors
        echo 'Error: ' . $e->getMessage();
    }
}
?>
