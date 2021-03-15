<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
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
        </section>
        <br><input id="payButton" class="button1" type="submit" name="pay" value="Pay €50">
        </form>


        <br>
        <section id="shareButtonBox">
            <form method="get">
                <input name="restaurantId" type="hidden" value="1">
                <button type="submit" class="button1" id="sharebutton" name="share" value="??"><img
                            src="https://static.thenounproject.com/png/37730-200.png"
                            height="20px"> Share with your friends!
                </button>
            </form>
        </section>


    </section>
</section>

</body>

</html>