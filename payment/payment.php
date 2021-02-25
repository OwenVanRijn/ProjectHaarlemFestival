<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment - account</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>Payment</h1>

    <section>
        <p>Step 2/3</p>
    </section>


    <form method="post" action="payment.php">
        <p>Select paying method</p>
        <input type="radio" id="ideal" name="paymethod" value="ideal">
        <label for="ideal">iDeal</label><br>
        <input type="radio" id="creditcard" name="paymethod" value="creditcard">
        <label for="creditcard">Creditcard</label><br>
        <input type="radio" id="visa" name="paymethod" value="visa">
        <label for="visa">Visa</label>
    </form>


    <button onclick=""><img src="https://static.thenounproject.com/png/37730-200.png"> Share with your friends!</button>

    </main>
</body>

</html>