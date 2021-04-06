<?php
session_start();
ini_set('display_errors', -1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/activity.php");
require_once($root . "/Service/activityService.php");
require_once($root . "/Service/jazzactivityService.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/danceActivityService.php");
require_once($root . "/Service/shoppingcartService.php");
require_once($root . "/Service/shoppingcartServiceDB.php");

require_once "../Email/mailer.php";
$total = $_SESSION['total'];

use Mollie\Api\MollieApiClient;
require_once "../lib/mollie/vendor/autoload.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_vqEjJvzKUW67F2gz3Mr3jzgpSs4drN");

$cusId = $_SESSION['id'];
$cartId = $_SESSION['cartId'];
$activitiesOrder = $_SESSION['cart'];

echo $cusId;
echo "<br>";
echo count($activitiesOrder);
echo "<br>";
echo $cartId;

if(isset($_POST['pay'])){

    $payment = $mollie->payments->create([
        "amount" => [
            "currency" => "EUR",
            "value" => "$total.00"
        ],
        "description" => "Haarlem Festival",
        "redirectUrl" => "https://haarlemfestival.louellacreemers.nl/success.php",
        "webhookUrl"  => "https://haarlemfestival.louellacreemers.nl/webhook.php?id=$cusId&cart=$cartId"
    ]);

    header("Location: " . $payment->getCheckoutUrl(), true, 303);
}

function setId($id){

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
            $activityDate = $activity->getDate()->format('Y-m-d');
          }
          else {
            $activityDate = $activity->getActivity()->getDate()->format('Y-m-d');
          }
          if(!in_array($activityDate,$days)){
            $days[] = $activityDate;
          }
        }
        foreach ($days as $day ) {
          echo "<h2>{$day}</h2>";
          echo "<table>";
          for($i = 0; $i < count($activitiesOrder); $i++){
            $activityDay = $activitiesOrder[$i]->getActivity()->getDate();
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
            if($activityDay == $day)
            {
              echo "<tr>";
              echo "<td>{$activityName}</td>";
              echo "</tr>";
            }
          }
          echo "</table>";
        }

         ?>
        <section id="paybox">
            <form method="post">
                <p>Select paying method</p>
                <input type="radio" id="ideal" name="paymethod" value="ideal">
                <label for="ideal"><img src="../img/Icons/ideallogo.png" height="20px"> iDeal</label><br>
                <input type="radio" id="creditcard" name="paymethod" value="creditcard">
                <label for="creditcard"><img src="../img/Icons/creditcardlogo.png" height="20px"> Creditcard</label><br>
                <input type="radio" id="visa" name="paymethod" value="visa">
                <label for="visa"><img src="../img/Icons/visalogo.gif" height="20px"> Visa</label>
            </form>
        </section>
        <br>

        <form method="post" action="payment.php">
        <input id="payButton" class="button1" type="submit" name="pay" value="Pay €<?php echo $total?>">
        </form>

    </section>
</section>

</body>

</html>
