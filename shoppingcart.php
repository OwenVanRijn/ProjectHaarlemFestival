<?php
require_once("Service/shoppingcart.php");

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        $result = $shoppingcartService->getShoppingcart()->getShoppingcartItems();
        
        $dates = array( '2021-07-26', '2021-07-27', '2021-07-28', '2021-07-29' );
        foreach ($dates as $date)
        {
            $result += echoDay($result, $date);
        }
    } else {
        echo "<p>Cart is Empty</p>";
    }

    ?>

</section>
<?php



function cartElement($productid, $activityName, $type, $createData, $startTime, $endTime, $price, $amount)
{
    $element = "
    
    <form action=\"cart.php?action=remove&id=$productid\" method=\"post\" class=\"cart-items\">
                    <section class=\"border rounded\">
                        <section class=\"row bg-white\">
                            <section class=\"col-md-6\">
                                <h5 class=\"pt-2\">$activityName</h5>
                                <small class=\"text-secondary\">Seller: dailytuition</small>
                                <h5 class=\"pt-2\">$price</h5>
                                <button type=\"submit\" class=\"btn btn-warning\">Save for Later</button>
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
    echo  $element;
}



function echoDay($result, $date)
{
    $dateString = $date->format('d-m-y');
    echo '<h2>' . date("D", strtotime($dateString)) . '</h2>';
    $product_id = array_column($_SESSION['shoppingcart'], 'id');

    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        foreach ($product_id as $id) {
            if ($row['id'] == $id && $row['date'] == $date) {
                cartElement($row['id'], $row['activityName'], $row['type'], $row['createData'], $row['date'], $row['startTime'], $row['endTime'], $row['price'], $row['amount']);
                $total = $total + ((float)$row['price'] * (float)$row['amount']);
            }
        }
    }
    
    return $total;
}


?>


</body>
</html>