<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");
require_once($root . "/Service/shoppingcartService.php");
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
    <link rel="stylesheet" href="css/dance.css">
</head>

<body>
<h1>Shoppingcart</h1>
<section>

    <?php


    $total = 0;
    if (isset($_SESSION['shoppingcart'])) {

        $shoppingcartService = new shoppingcartService();
        $jazzActivityService = new jazzActivityService();
        $foodActivityService = new foodActivityService();
        $danceActivityService = new danceActivityService();
        $shoppingcart = $shoppingcartService->getShoppingcart()->getShoppingcartItems();

        echo "<br><br> shoppingcart <br><br>";
        var_dump($shoppingcart);
        echo "<br><br> shoppingcart <br><br>";


        $ids = array();
        foreach ($shoppingcart as $key => $value) {
            $ids[] = $key;
        }
        echo "<br><br> IDS <br><br>";
        var_dump($ids);
        echo "<br><br> IDS <br><br>";

        $foodActivityService->getFromActivityIds([3, 1]);

        echo "werkt nog";

        $activities = array_merge($danceActivityService->getFromActivityIds($ids), $foodActivityService->getFromActivityIds($ids), $jazzActivityService->getFromActivityIds($ids));

        $dates = array('2021-06-26', '2021-06-27', '2021-06-28', '2021-06-29');


        $days = array();
        $thursdayActivities = array();
        $fridayActivities = array();
        $saturdayActivities = array();
        $sundayActivities = array();

        foreach ($activities as $activity) {
            if ($activity->getActivity()->getDate()->format("Y-m-d") == $dates[0]) {
                $thursdayActivities[] = $activity;
            }
            if ($activity->getActivity()->getDate()->format("Y-m-d") == $dates[1]) {
                $fridayActivities[] = $activity;
            }
            if ($activity->getActivity()->getDate()->format("Y-m-d") == $dates[2]) {
                $saturdayActivities[] = $activity;
            }
            if ($activity->getActivity()->getDate()->format("Y-m-d") == $dates[3]) {
                $sundayActivities[] = $activity;
            }
        }

        $days[0] = $thursdayActivities;
        $days[1] = $fridayActivities;
        $days[2] = $saturdayActivities;
        $days[3] = $sundayActivities;

        for ($i = 0; $i < count($days); $i++) {
            if ($days[$i] != 0) {
                $total += echoDay($dates[$i], $days[$i]);
            }
        }
        if (isset($_POST["pay"])) {
            header("Location: /payment/account.php");
        }

        ?>
        <form method="post">
            <input type="submit" class="btn btn-primary" name="pay" value="Pay €<?php echo $total ?>"/>
        </form>
        <?php

        echo "done";
    } else {
        echo "<p>Cart is Empty</p>";
    }

    ?>

</section>
<?php


function echoDay($date, $activitiesOfTheDay)
{
    $totalPriceDay = 0;
    echoTitles($date);

    //echo "<br><br> ACTIVITY ON DAY <br><br>";
    //var_dump($activitiesOfTheDay);
    //echo get_class($activitiesOfTheDay[0]);
    //echo "<br><br> ACTIVITY ON DAY <br><br>";

    foreach ($activitiesOfTheDay as $activity) {
        $price = $activity->getActivity()->getPrice();
        $amount = 1;
        $totalPriceActivity = $amount * $price;
        $type = $activity->getActivity()->getType();
        $activityId = $activity->getActivity()->getId();
        $startTime = $activity->getActivity()->getStartTime();
        $endTime = $activity->getActivity()->getEndTime();

        if (get_class($activity) == "foodactivity") {
            $activityName = $activity->getRestaurant()->getName();
        } else if (get_class($activity) == "jazzactivity") {
            $activityName = $activity->getJazzband()->getName();
        } else {
            $activityName = "dance activity";
        }

        $shoppingcartService = new shoppingcartService();
        $amount = $shoppingcartService->getAmountByActivityId($activityId);
        cartElement($activityId, $activityName, $type, date("Y-m-d"), $startTime->format('H:i:s'), $endTime->format('H:i:s'), $price, $amount);


        $totalPriceDay += $totalPriceActivity;
    }


    return $totalPriceDay;


    echo '<h2>' . date("D", strtotime($date)) . ' (' . $date . ')</h2>';
    //$product_id = array_column($_SESSION['shoppingcart'], 'id');

    $total = 0;
    foreach (unserialize($_SESSION['shoppingcart']) as $id) {

        echo "<br>Gegeven ID is $id<br>";
        //$result = $shoppingcartService->getInformationById($id);
        $result = $this->shoppingcartService->getEventActivityInformationById($id);

    }

    return $total;
}


function echoTitles($date)
{
    $element = "
    <section class=\"border rounded\">
    <section class=\"row bg-white\">
        <section class=\"col-md-6\">
         <h1 class=\"pt-2\">$date</h1>
            <p class=\"titleInfo\">Amount</p>
            <p class=\"titleInfo\">Event</p>
            <p class=\"titleInfo\">Type</p>
            <p class=\"titleInfo\">Time</p>
            <p class=\"titleInfo\">Price</p>
            <p class=\"titleInfo\">Totalprice</p>
        </section>
    </section>
</section>
";

    echo $element;
}

if (isset($_POST['remove'])){
    if ($_GET['action'] == 'remove'){
        $shoppingcartService->removeFromShoppingcartItemsById($_GET["id"]);
    }
    else if ($_GET['action'] == 'remove'){
        $shoppingcartService->removeFromShoppingcartItemsById($_GET["id"]);
    }

    header("Refresh:0");
}

function cartElement($activityid, $activityName, $type, $createData, $startTime, $endTime, $price, $amount)
{
    $totalPrice = $amount * $price;

    $element = "
    
    <form action=\"shoppingcart.php?action=remove&id=$activityid\" method=\"post\" class=\"cart-items\">
                    <section class=\"border rounded\">
                        <section class=\"row bg-white\">
                            <section class=\"col-md-6\">
                                <h3 class=\"pt-2\">$activityName $activityid</h3>
                                <p class=\"titleInfo\">$type</p>
                                <p class=\"titleInfo\">$startTime-$endTime</p>
                                <p class=\"titleInfo\">€$price</p>
                                <p class=\"titleInfo\">€$totalPrice</p>
                                <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
                            </section>
                            <section class=\"col-md-3 py-5\">
                                <section>
                                    <button type=\"button\" class=\"btn bg-light border rounded-circle\"><i class=\"fas fa-minus\"></i></button>
                                    <input type=\"text\" value=\"$amount\" class=\"form-control w-25 d-inline\">
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