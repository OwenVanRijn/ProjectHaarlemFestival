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

    <script language=Javascript>
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>
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

        if (count($ids) == 0) {
            echo "<p>Cart is Empty</p>";
        } else {
            echo "<br><br> IDS <br><br>";
            var_dump($ids);
            echo "<br><br> IDS <br><br>";

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

            ?>
            <button class="btn btn-primary"
                    onclick="window.location.href='/payment/account.php'"><?php echo "Pay €$total" ?> </button>
            <?php
        }
    } else {
        echo "<p>Cart is Empty</p>";
    }

    ?>

</section>
<?php


if (isset($_POST["edit"]) || isset($_POST["remove"])) {
    echo "SET IS TRUE";
    if ($_GET['action'] == 'remove') {
        $shoppingcartService->removeFromShoppingcartItemsById($_GET["id"]);
    } else if ($_GET['action'] == 'edit') {
        $newAmount = $_POST["amount"];
        if ($newAmount == 0) {
            $shoppingcartService->removeFromShoppingcartItemsById($_GET["id"]);
        } else {
            $shoppingcartService->getShoppingcart()->setShoppingcartItemById($_GET["id"], $newAmount);
        }
    }
    //header("Refresh:0");
    $page = basename($_SERVER["SCRIPT_FILENAME"]);
    echo "<script>window.location.href = '$page';</script>";
}


function echoDay($date, $activitiesOfTheDay)
{
    if (count($activitiesOfTheDay) != 0) {
        $totalPriceDay = 0;
        echoTitles($date);


        //echo "<br><br> ACTIVITY ON DAY <br><br>";
        //var_dump($activitiesOfTheDay);
        //echo get_class($activitiesOfTheDay[0]);
        //echo "<br><br> ACTIVITY ON DAY <br><br>";

        foreach ($activitiesOfTheDay as $activity) {
            $price = $activity->getActivity()->getPrice();
            $activityId = $activity->getActivity()->getId();
            $shoppingcartService = new shoppingcartService();
            $amount = $shoppingcartService->getAmountByActivityId($activityId);
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
            cartElement($activityId, $activityName, $type, date("Y-m-d"), $startTime->format('H:i'), $endTime->format('H:i'), $price, $amount);


            $totalPriceDay += $totalPriceActivity;
        }


        return $totalPriceDay;
    }
    return 0;
}


function echoTitles($date)
{
    $element = "
    <section class=\"border rounded\">
    <section class=\"row bg-white\">
        <section class=\"col-md-6\">
         <h2 class=\"pt-2\">$date</h2>
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

function cartElement($activityid, $activityName, $type, $createData, $startTime, $endTime, $price, $amount)
{
    $totalPrice = $amount * $price;

    $element = "
    
                        <section class=\"border rounded\">
                        <section class=\"row bg-white\">
                            <section class=\"col-md-6\">
                                <h3 class=\"pt-2\">$activityName $activityid</h3>
                                <p class=\"titleInfo\">$type</p>
                                <p class=\"titleInfo\">$startTime-$endTime</p>
                                <p class=\"titleInfo\">€$price</p>
                                <p class=\"titleInfo\">€$totalPrice</p>
                                <form action=\"shoppingcart.php?action=remove&id=$activityid\" method=\"post\" class=\"cart-items\">
                                <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
                                              </form>
                            </section>
                            <section class=\"col-md-3 py-5\">
                                <section>
                                    <form action=\"shoppingcart.php?action=edit&id=$activityid\" method=\"post\" class=\"cart-items\">
                                        <input type=\"text\" onkeypress=\"return isNumberKey(event)\" value=\"$amount\" class=\"form-control w-25 d-inline\" name=\"amount\">
                                        <button type=\"submit\" class=\"btn bg-light border rounded-circle\" name=\"edit\">Set</button>
                                    </form>
                                </section>
                            </section>
                        </section>
                    </section>
  
    
    ";
    echo $element;
}


?>


</body>

</html>