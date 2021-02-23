<?php
session_start();
require_once("Service/shoppingcartService.php");
require_once "UI/navBar.php";
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoppingcart</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<h1>Shoppingcart</h1>
<section>

    <?php

    $shoppingcartService = new shoppingcartService();


    $total = 0;
    if (isset($_SESSION['shoppingcart'])) {

        //echo "VARDUMP " . var_dump($_SESSION['shoppingcart']) . "<br>";
        //echo "PRINT " . print_r($_SESSION['shoppingcart']) . "<br>";

        //$result = $shoppingcartService->getShoppingcart()->getShoppingcartItems();


        //$result = $shoppingcartService->getInformationById(1);
        //echo "VARDUMP " . var_dump($result) . "<br><br><br>";
        //echo "PRINT " . print_r($result) . "<br><br><br>";


        $dates = array('2021-06-26', '2021-06-27', '2021-06-28', '2021-06-29');
        foreach ($dates as $date) {
            $total += echoDay($date);
        }

        echo "done";
    } else {
        echo "<p>Cart is Empty</p>";
    }

    ?>

</section>
<?php


function echoDay($date)
{
    global $shoppingcartService;

    echoTitles();

    echo '<h2>' . date("D", strtotime($date)) . ' (' . $date . ')</h2>';
    //$product_id = array_column($_SESSION['shoppingcart'], 'id');

    $total = 0;
    foreach (unserialize($_SESSION['shoppingcart']) as $id) {

        echo "<br>Gegeven ID is $id<br>";
        //$result = $shoppingcartService->getInformationById($id);
        $result = $shoppingcartService->getEventActivityInformationById($id);

        var_dump($result);
        echo "<br> INFO: " . $result[0]["name"];
        while ($row = mysqli_fetch_assoc($result)) {


            if ($row['id'] == $id && $row['date'] == $date) {
                cartElement($row['id'], $row['activityName'], $row['type'], $row['createData'], $row['date'], $row['startTime'], $row['endTime'], $row['price'], $row['amount']);
                $total = $total + ((float)$row['price'] * (float)$row['amount']);
            }
        }
    }

    return $total;
}


function echoTitles()
{
    $element = "
    <section class=\"border rounded\">
    <section class=\"row bg-white\">
        <section class=\"col-md-6\">
            <h3 class=\"pt-2\">Amount</h3>
            <p class=\"titleInfo\">Event</p>
            <p class=\"titleInfo\">Type</p>
            <p class=\"titleInfo\">Time</p>
            <p class=\"titleInfo\">Price</p>
            <p class=\"titleInfo\">Totalprice</p>
            <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
        </section>
        <section class=\"col-md-3 py-5\">
            <section>
                <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-minus\"></i></button>
                <input type=\"text\" value=\"1\" class=\"form-control w-25 d-inline\">
                <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-plus\"></i></button>
            </section>
        </section>
    </section>
</section>
";

    echo $element;
}


function cartElement($productid, $activityName, $type, $createData, $startTime, $endTime, $price, $amount)
{
    $totalPrice = $amount * $price;

    $element = "
    
    <form action=\"cart.php?action=remove&id=$productid\" method=\"post\" class=\"cart-items\">
                    <section class=\"border rounded\">
                        <section class=\"row bg-white\">
                            <section class=\"col-md-6\">
                                <h3 class=\"pt-2\">$activityName</h3>
                                <p class=\"titleInfo\">$type</p>
                                <p class=\"titleInfo\">$startTime-$endTime</p>
                                <p class=\"titleInfo\">€$price</p>
                                <p class=\"titleInfo\">€$totalPrice</p>
                                <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
                            </section>
                            <section class=\"col-md-3 py-5\">
                                <section>
                                    <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-minus\"></i></button>
                                    <input type=\"text\" value=\"1\" class=\"form-control w-25 d-inline\">
                                    <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-plus\"></i></button>
                                </section>
                            </section>
                        </section>
                    </section>
                </form>
    
    ";
    echo $element;
}


?>


</body>

</html>