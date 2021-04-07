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

//try {
//    require_once($root . "/Service/shoppingcartServiceDB.php");
//    $shoppingcartServiceDB = new shoppingcartServiceDB();
//    $cartId = $shoppingcartServiceDB->addShoppingcartToDatabase();
//}
//catch(Exception $exception)
//{
//    echo "Niet gelukt: {$exception->getMessage()}";
//}

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
    <link rel="stylesheet" href="css/shoppingcart.css">
</head>

<body>
<?php
require_once($root . "/UI/navBar.php");
?>
<section class="contentShoppingcart content">
    <br>
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
                } else if ($_POST['action'] == 'edit') {
                    $newAmount = $_POST["amount"];
                    if (!intval($newAmount)) {
                        echo "Could set the amount in the shoppingcart. Amount is not valid.";
                    } elseif ($newAmount <= 0) {
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

            $ids = array();
            foreach ($shoppingcart as $key => $value) {
                $ids[] = $key;
            }

            if (count($ids) == 0) {
                echo "<p>Cart is Empty</p>";
            } else {
                try {
                    $activities = array_merge($danceActivityService->getFromActivityIds($ids), $foodActivityService->getFromActivityIds($ids), $jazzActivityService->getFromActivityIds($ids), $activityService->getAllById($ids));

                    $_SESSION['cart'] = $activities;

                    if ($activities != null && count($activities) != 0) {
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

                        function date_sort($a, $b)
                        {
                            return strtotime($a) - strtotime($b);
                        }

                        usort($datesOfFestival, "date_sort");

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
                        <form method="post" action="/payment/account.php">
                            <input class="payOrder stepNext" type="submit" name="payconfirm"
                                   value="<?php echo "Pay €$total"; ?>">
                        </form>
                        <?php
                    } else {
                        echo "<p>Cart is Empty</p>";
                    }
                } catch (Exception $exception) {
                    echo "Cant find activities from database";
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
        $shoppingcartService = new shoppingcartService();

        if (count($activitiesOfTheDay) != 0) {
            $totalPriceDay = 0;

            // Bereken de totaalPrijs voor de dag.
            foreach ($activitiesOfTheDay as $activity) {
                if (get_class($activity) == "activity") {
                    $activityOTD = $activity;
                } else {
                    $activityOTD = $activity->getActivity();
                }
                $activityId = $activityOTD->getId();
                $amount = $shoppingcartService->getAmountByActivityId($activityId);
                $price = $activityOTD->getPrice();
                $activityId = $activityOTD->getId();
                $totalPriceActivity = $amount * $price;

                $totalPriceDay += $totalPriceActivity;
            }
            // Echo de labels
            echoTitles($date, $totalPriceDay);

            // Voor elke activiteit van de dag : echo de activiteit
            foreach ($activitiesOfTheDay as $activity) {

                if (get_class($activity) == "activity") {
                    $activityOTD = $activity;
                } else {
                    $activityOTD = $activity->getActivity();
                }


                $price = $activityOTD->getPrice();
                $activityId = $activityOTD->getId();
                $amount = $shoppingcartService->getAmountByActivityId($activityId);
                $type = $activityOTD->getType();
                $activityId = $activityOTD->getId();
                $startTime = $activityOTD->getStartTime();

                $endTime = $activityOTD->getEndTime();

                if (get_class($activity) == "foodactivity") {
                    $activityName = $activity->getRestaurant()->getName();
                } else if (get_class($activity) == "jazzactivity") {
                    $activityName = $activity->getJazzband()->getName();
                } else if (get_class($activity) == "danceActivity") {
                    $artists = $activity->getArtists();
                    $artistNames = array();
                    foreach ($artists as $artist) {
                        $artistNames[] = $artist->getName();
                    }
                    $activityName = implode(", ", $artistNames);
                } else {
                    $activityName = $activity->getType();
                }

                $shoppingcartService = new shoppingcartService();
                $amount = $shoppingcartService->getAmountByActivityId($activityId);
                cartElement($activityId, $activityName, $type, date("Y-m-d"), $startTime->format('H:i'), $endTime->format('H:i'), $price, $amount);
            }

            return $totalPriceDay;
        }
        return 0;
    }


    function echoTitles($date, $totalPriceDay)
    {
        $dateString = DateTime::createFromFormat("Y-m-d", $date);
        $dateString = $dateString->format("l jS F");
        $element = "
        <h2 class=\"labelOfDay\">$dateString</h2>
        <h2 class=\"labelOfDay labelTotalOfDay\">Total $dateString: €$totalPriceDay</h2>
        <hr>
    <section class=\"containerShoppingcart\">
        <section class=\"titleAmount\"><h3>Amount</h3></section>
        <section class=\"titleActivityEvent\"><h3>Event</h3></section>
        <section class=\"titleType\"><h3>Type</h3></section>
        <section class=\"titleTime\"><h3>Time</h3></section>
        <section class=\"titlePrice\"><h3>Price</h3></section>
        <section class=\"titleTotalPrice\"><h3>Totalprice</h3></section>
        <section class=\"buttonRemove\"></section>
    </section>
";

        echo $element;
    }

    function cartElement($activityid, $activityName, $type, $createData, $startTime, $endTime, $price, $amount)
    {
        //echo het cart element, de activiteit.
        $totalPrice = $amount * $price;
        $element = "
    <section class=\"containerShoppingcart\">
        <section class=\"titleAmount\">
        <form style='display:inline' method=\"post\" class=\"cart-items\">
                                        <input type=\"hidden\" name=\"action\" value=\"edit\"/>
                                        <input type=\"hidden\" name=\"id\" value=\"$activityid\"/>
                                        <input type=\"text\" onkeypress=\"return isNumberKey(event)\" onchange=\"document.getElementById('btnSubmitEdit$activityid').click()\" value=\"$amount\" class=\"form-control w-25 d-inline\" name=\"amount\">
                                        <button type=\"submit\" id=\"btnSubmitEdit$activityid\" style=\"visibility: hidden\" name=\"edit\">Set</button>
                                    </form>
</section>
        <section class=\"titleActivityEvent\"><p>$activityName ACTid $activityid</p></section>
        <section class=\"titleType\"><p>$type</p></section>
        <section class=\"titleTime\"><p>$startTime-$endTime</p></section>
        <section class=\"titlePrice\"><p>€$price</p></section>
        <section class=\"titleTotalPrice\"><p>€$totalPrice</p></section>
        <section class=\"buttonRemove\">
        <form method=\"post\" class=\"cart-items\">
                                        <input type=\"hidden\" name=\"action\" value=\"remove\"/>
                                        <input type=\"hidden\" name=\"id\" value=\"$activityid\"/>
                                     <button type=\"submit\" class=\"btnRemove\" name=\"remove\">
                                        <img src=\"/img/Icons/remove.png\" class=\"btnRemoveIMG\"/>
                                        </button>
                                              </form>
</section>
    </section>
    ";
        echo $element;
    }

    ?>
</section>
</body>

</html>
