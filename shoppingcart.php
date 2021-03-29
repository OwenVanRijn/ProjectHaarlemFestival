<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Email/mailer.php");
require_once($root . "/Model/activity.php");
require_once($root . "/Service/activityService.php");
require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");
require_once($root . "/Service/shoppingcartService.php");
require_once($root . "/Service/shoppingcartServiceDB.php");


$mailer = new mailer();
$shoppingcartServiceDB = new shoppingcartServiceDB();
//$cartId = $shoppingcartServiceDB->addShoppingcartToDatabase();

//$mailer->sendMail("louellacreemers@gmail.com", "CartId", "ID = {$cartId}");
//$_SESSION['cartId'] = $cartId;
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
<?php
require_once($root . "/UI/navBar.php");
?>
<h1>Shoppingcart</h1>
<section>

    <script language=Javascript>
        //        zorg ervoor dat men geen tekst invoert.
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>

    <?php

    // VERWIJDER OF BEWERK een shoppingcart item
    if (isset($_POST["edit"]) || isset($_POST["remove"])) {
        $shoppingcartService = new shoppingcartService();

        $idOfActivity = $_POST["id"];
        if (intval($idOfActivity)) {
            if ($_POST['action'] == 'remove') {
                $shoppingcartService->removeFromShoppingcartItemsById($idOfActivity);
            } else if ($_POST['action'] == 'edit') {
                $newAmount = $_POST["amount"];
                if ($newAmount == 0) {
                    $shoppingcartService->removeFromShoppingcartItemsById($idOfActivity);
                } else {
                    $shoppingcartService->getShoppingcart()->setShoppingcartItemById($idOfActivity, $newAmount);
                }
            }
        }
    }


    // Haal shoppingcart op.
    $total = 0;
    if (isset($_SESSION['shoppingcart'])) {
        $shoppingcartService = new shoppingcartService();
        $jazzActivityService = new jazzActivityService();
        $foodActivityService = new foodActivityService();
        $danceActivityService = new danceActivityService();
        $activityService = new activityService();
        try {
            $shoppingcart = $shoppingcartService->getShoppingcart()->getShoppingcartItems();
        } catch (Exception $exception) {
            echo "<p>Cant get the shoppingcartitems. Please try again later or reset your cookies.</p>";
        }

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


            echo "<br><br> SHOPPINGCARTITEMSDB <br><br>";
            $shoppingcartItemsDB = $shoppingcartServiceDB->getShoppingcart();
            var_dump($shoppingcartItemsDB);

            echo "<br><br> SHOPPINGCARTITEMSDB <br><br>";


            try {
                $activities = array_merge($danceActivityService->getFromActivityIds($ids), $foodActivityService->getFromActivityIds($ids), $jazzActivityService->getFromActivityIds($ids), $activityService->getAllById($ids));
            }
            catch(Exception $exception){
                echo "Cant find activities from database";
            }

            $_SESSION['cart'] = $activities;

            if ($activities != null && count($activities) != 0) {
                echo "<br><br> ACTIVITYES <br><br>";
                var_dump($activities);
                echo "<br><br> ACTIVITYES <br><br>";


                // verkrijg voor elke dag de activiteiten
                $datesOfFestival = array();

                foreach ($activities as $activity) {
                    if (get_class($activity) == "activity") {
                        $activityDate = $activity->getDate()->format("Y-m-d");
                    } else {
                        $activityDate = $activity->getActivity()->getDate()->format("Y-m-d");
                    }

                    if (!in_array($activityDate, $datesOfFestival)) {
                        $datesOfFestival[] = $activityDate;
                    }
                }

                $dayActivities = array();
                for ($index = 0; $index <= count($datesOfFestival) - 1; $index++) {
                    $activitiesOfThisDay = array();
                    foreach ($activities as $activity) {

                        if (get_class($activity) == "activity") {
                            $dateOfActivity = $activity->getDate()->format("Y-m-d");
                        } else {
                            $dateOfActivity = $activity->getActivity()->getDate()->format("Y-m-d");
                        }

                        if ($dateOfActivity == $datesOfFestival[$index]) {
                            $activitiesOfThisDay[] = $activity;
                        }
                    }
                    $dayActivities[] = $activitiesOfThisDay;
                }

                for ($i = 0; $i < count($dayActivities); $i++) {
                    if ($dayActivities[$i] != 0) {
                        // echo alle activiteiten van de dag en bereken het totaal.
                        $total += echoDay($datesOfFestival[$i], $dayActivities[$i]);
                    }
                }
                $_SESSION['total'] = $total;
                ?>
                <button class="button1"
                        onclick="window.location.href='/payment/account.php'"><?php echo "Pay €$total" ?> </button>
                <?php
            } else {
                echo "<p>Cart is Empty</p>";
            }
        }
    } else {
        echo "<p>Cart is Empty</p>";
    }

    ?>

</section>
<?php


function echoDay($date, $activitiesOfTheDay)
{
    if (count($activitiesOfTheDay) != 0) {
        $totalPriceDay = 0;

        // Echo de labels
        echoTitles($date);

        // Voor elke activiteit van de dag : echo de activiteit
        foreach ($activitiesOfTheDay as $activity) {

            if (get_class($activity) == "activity") {
                $activityOTD = $activity;
            } else {
                $activityOTD = $activity->getActivity();
            }


            $price = $activityOTD->getPrice();
            $activityId = $activityOTD->getId();
            $shoppingcartService = new shoppingcartService();
            $amount = $shoppingcartService->getAmountByActivityId($activityId);
            $totalPriceActivity = $amount * $price;
            $type = $activityOTD->getType();
            $activityId = $activityOTD->getId();
            $startTime = $activityOTD->getStartTime();

            $endTime = $activityOTD->getEndTime();

            if (get_class($activity) == "foodactivity") {
                $activityName = $activity->getRestaurant()->getName();
            } else if (get_class($activity) == "jazzactivity") {
                $activityName = $activity->getJazzband()->getName();
            } else if (get_class($activity) == "danceactivity") {
                $artists = $activity->getArtists();
                $artistNames = array();
                foreach ($artists as $artist) {
                    $artistNames[] = $artist->getName();
                }
                $activityName = implode(" ", $artistNames);
            } else {
                $activityName = $activity->getType();
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
    //echo het cart element, de activiteit.
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
                           
                                <form method=\"post\" class=\"cart-items\">
                                        <input type=\"hidden\" name=\"action\" value=\"remove\"/>
                                        <input type=\"hidden\" name=\"id\" value=\"$activityid\"/>
                                     <button type=\"submit\" class=\"btn btn-danger mx-2\" name=\"remove\">Remove</button>
                                              </form>
                            </section>
                            <section class=\"col-md-3 py-5\">
                                <section>
                                    <form method=\"post\" class=\"cart-items\">
                                        <input type=\"hidden\" name=\"action\" value=\"edit\"/>
                                        <input type=\"hidden\" name=\"id\" value=\"$activityid\"/>
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