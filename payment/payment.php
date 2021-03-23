<?php
session_start();

if(isset($_SESSION['cart'])){
    $cart = $_SESSION['cart'];

    foreach ($cart as $item){
        print_r($item);
    }
}

else{
    echo 'not cart';
}

$total = $_SESSION['total'];

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");

use Mollie\Api\MollieApiClient;
require_once "../lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

if(isset($_POST['pay'])){

    $payment = $mollie->payments->create([
        "amount" => [
            "currency" => "EUR",
            "value" => "$total.00"
        ],
        "description" => "Haarlem Festival",
        "redirectUrl" => "https://google.com",
        "webhookUrl"  => $root . "payment/webhook.php"
    ]);

    header("Location: " . $payment->getCheckoutUrl(), true, 303);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment - account</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/shoppingcart.css">
    <meta charset="UTF-8">
    <meta name="keywords"
          content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<section class="contentShoppingcart content">
    <section>
        <h1>Payment</h1>
        <section>
            <p class="step">Step 2 / 3</p>

        </section>

        <section id="paybox">
            <form method="post" action="payment.php">
                <p>Select paying method</p>
                <input type="radio" id="ideal" name="paymethod" value="ideal">
                <label for="ideal"><img src="../img/Icons/ideallogo.png" height="20px"> iDeal</label><br>
                <input type="radio" id="creditcard" name="paymethod" value="creditcard">
                <label for="creditcard"><img src="../img/Icons/creditcardlogo.png" height="20px"> Creditcard</label><br>
                <input type="radio" id="visa" name="paymethod" value="visa">
                <label for="visa"><img src="../img/Icons/visalogo.gif" height="20px"> Visa</label>
            </form>
        </section>
        <br>

        <form method="post" action="payment.php">
        <input id="payButton" class="button1" type="submit" name="pay" value="Pay €<?php echo $total?>">
        </form>

    </section>
</section>

</body>

</html>