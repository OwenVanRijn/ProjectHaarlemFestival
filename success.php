<?php
session_start();
require_once "UI/navBar.php";

use Mollie\Api\MollieApiClient;
require_once "lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

var_dump($mollie->payments);
?>


<!DOCTYPE html>
<html>

<head>
    <title>Success - Haarlem Festival</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<h2> You're going to Haarlem Festival! Your orderid is:
</h2>

<p> We sent you an email with your tickets!</p>
</body>
</html>