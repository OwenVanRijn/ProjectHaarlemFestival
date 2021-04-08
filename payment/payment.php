<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/activity.php");
require_once($root . "/Service/activityService.php");
require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");
require_once($root . "/Service/shoppingcartService.php");
require_once($root . "/Service/shoppingcartServiceDB.php");
require_once "../Email/mailer.php";
require_once "../lib/mollie/vendor/autoload.php";

$total = $_SESSION['total']; //total price
$cusId = $_SESSION['id']; //customerId
$activitiesOrder = $_SESSION['cart']; //cart


use Mollie\Api\MollieApiClient; //calls Mollie API client

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN"); //Test api key

$shoppingcartService = new shoppingcartService();

if(isset($_POST['pay'])){ //if Pay button is clicked
    if(preg_match("/^[0-9]*$/",$total)){ //if value contains anything but a number
        if ((int)$total > 0) { //if value isn't under 0
            //CART TO DB
            $shoppingcartServiceDB = new shoppingcartServiceDB();
            $cartId = $shoppingcartServiceDB->addShoppingcartToDatabase();
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => "$total.00"
                ],
                "description" => "Haarlem Festival",
                "redirectUrl" => "https://haarlemfestival.louellacreemers.nl/success.php", //url to go to if successful
                "webhookUrl" => "https://haarlemfestival.louellacreemers.nl/webhook.php?id=$cusId&cart=$cartId"
            ]);

            header("Location: " . $payment->getCheckoutUrl(), true, 303); //go to success page after payment
        }

        else{
            header("Location: ../paymenterror.php");
        }
    }
    else{
        header("Location: ../paymenterror.php");
    }
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
        <?php
        $days = array();

        foreach ($activitiesOrder as $activity ) {
            if(get_class($activity) == 'activity')
            {
                $activityDate = $activity->getDate()->format('l jS F');
            }
            else {
                $activityDate = $activity->getActivity()->getDate()->format('l jS F');
            }
            if(!in_array($activityDate,$days)){
                $days[] = $activityDate;
            }
        }
        foreach ($days as $day) {
            echo "<h2>{$day}</h2>";
            echo "<table style='width:100%; border-top:1px solid #000'>";
            echo "<th style='width:20%'>amount</th>";
            echo "<th style='width:20%'>event</th>";
            //echo "<th style='width:20%'>type</th>";
            echo "<th style='width:20%'>time</th>";
            echo "<th style='width:20%'>price</th>";

            for($i = 0; $i < count($activitiesOrder); $i++){
                $price = $activitiesOrder[$i]->getActivity()->getPrice();
                $activityId = $activitiesOrder[$i]->getActivity()->getId();
                $activityDay = $activitiesOrder[$i]->getActivity()->getDate()->format('l jS F');
                //$activityType = $activitiesOrder[$i]->getActivity()->getType();
                $activityStart = $activitiesOrder[$i]->getActivity()->getStartTime()->format("H:i");
                $activityEnd = $activitiesOrder[$i]->getActivity()->getEndTime()->format("H:i");
                $amount = $shoppingcartService->getAmountByActivityId($activityId);
                $totalPriceActivity = '€' . $amount * $price;
                if (get_class($activitiesOrder[$i]) == "foodactivity") {
                    $activityName = $activitiesOrder[$i]->getRestaurant()->getName();
                }
                else if (get_class($activity) == "jazzactivity") {
                    $activityName = $activitiesOrder[$i]->getJazzband()->getName();
                }
                else if (get_class($activitiesOrder[$i]) == "danceActivity") {
                    $artists = $activitiesOrder[$i]->getArtists();
                    $artistNames = array();
                    foreach ($artists as $artist) {
                        $artistNames[] = $artist->getName();
                    }
                    $activityName = implode(", ", $artistNames);
                }
                else {
                    //$activityName = $activitiesOrder[$i]->getType();
                }
                if($activityDay == $day)
                {
                    echo "<tr>";
                    echo "<td style='text-align:center'>{$amount}</td>";
                    echo "<td style='text-align:center'>{$activityName}</td>";
                    //echo "<td style='text-align:center'>{$activityType}</td>";
                    echo "<td style='text-align:center'>{$activityStart} to {$activityEnd}</td>";
                    echo "<td style='text-align:center'>{$totalPriceActivity}</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";

        }
        echo "<p style='padding-right: 8%; float:right;'>Total: €{$total}</p>";
        ?>
        <br>

        <form style="margin-left:40%; margin-top:8%;" method="post" action="payment.php">
            <input id="payButton" class="button1" type="submit" name="pay" value="Pay €<?php echo $total?>">
        </form>

    </section>
</section>

</body>

</html>