<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/shoppingcartService.php");

$foodactivityService = new foodactivityService();


if (!isset($_POST["restaurantId"])) {
    header("Location: food.php", true, 301);
    exit();
} else {
    $restaurantId = $_POST["restaurantId"];
}
$foodactivities = $foodactivityService->getByRestaurantId($restaurantId);

if (isset($_POST["reservation"])) {
    if (isset($_POST["session"]) && isset($_POST["date"])) {
        $restaurantId = $_POST["restaurantId"];
        $seats = $_POST["seats"];
        $session = $_POST["session"];
        $date = $_POST["date"];

        try {
            $times = explode("-", $session);
            $foodactivity = $foodactivityService->getBySessionDate($date, $times, $restaurantId);

            if ($foodactivity == NULL) {
                echo "Could not find a valid activity. Please choose a valid date and session.";
            } else {
                $shoppingcartService = new shoppingcartService();
                $shoppingcartService->getShoppingcart()->addToShoppingcartItemsById($foodactivity->getActivity()->getId(), $seats);

                header("Location: food.php", true, 301);
                $_SESSION["foodreservationName"] = $foodactivity->getRestaurant()->getName();
                exit();
            }
        } catch (Exception $exception) {
            echo "Could not create an reservation. Please try again.";
        }
    }
    else
    {
        echo "Please select an session and date.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Food</title>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords"
          content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
<section class="w3-container">
    <section id="id01" class="w3-modal">
        <section class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
            <form class="w3-container" method="post">

                <h1><?php echo $foodactivities[0]->getRestaurant()->getName() ?></h1>

                <section class="reservationsection">
                    <h2 class="labelTitle">Amount of seats<br></h2>
                    <select name="seats" id="seats">
                        <option value="1">1 seat</option>
                        <option value="2">2 seats</option>
                        <option value="3">3 seats</option>
                        <option value="4">4 seats</option>
                        <option value="5">5 seats</option>
                    </select>
                </section>

                <br>

                <section class="reservationsection">
                    <h2 class="labelTitle">Session<br></h2>
                    <?php
                    $index = 1;

                    $times = getTimes($foodactivities);

                    foreach ($times as $startTimeStr => $endTimeStr) {
                        echo "<input type=\"radio\" class=\"session\" name=\"session\" id=\"session$index\" value=\"$startTimeStr-$endTimeStr\" required>";
                        echo "<label for=\"session$index\">$startTimeStr - $endTimeStr</label><br>";
                        $index++;
                    }
                    ?>
                </section>

                <section class="reservationsection">
                    <h2 class="labelTitle">Date<br></h2>
                    <?php
                    $index = 1;

                    $dates = getDates($foodactivities);

                    foreach ($dates as $date) {
                        echo "<input type=\"radio\" class=\"date\" name=\"date\" id=\"date$index\" value=\"$date\" required>";
                        echo "<label for=\"date$index\">$date</label><br>";
                        $index++;
                    }
                    ?>
                </section>


                <section class="reservationsection">
                    <h2 class="labelTitle">Note<br></h2>
                    <p>Do you have any dietary requirements, allergies or other comments?</p>
                    <textarea id="noteTextArea" rows="3" name="notes"></textarea>
                </section>


                <section class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                    <input type="button" onclick="location.href='food.php'" value="Cancel"/>
                    <?php
                    echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
                    ?>
                    <input class="w3-button w3-green w3-right w3-padding" type="submit" name="reservation"
                           id="session3" value="Make a reservation">
                </section>
            </form>

        </section>
    </section>
</section>
</body>
</html>


<?php

function getTimes($foodactivities)
{
    $times = array();

    foreach ($foodactivities as $foodactivity) {
        $startTime = $foodactivity->getActivity()->getStartTime();
        $endTime = $foodactivity->getActivity()->getEndTime();
        $startTimeStr = date_format($startTime, 'H:i');
        $endTimeStr = date_format($endTime, 'H:i');

        $times["$startTimeStr"] = $endTimeStr;
    }
    return $times;
}

function getDates($foodactivities)
{
    $dates = array();

    foreach ($foodactivities as $foodactivity) {
        $date = $foodactivity->getActivity()->getDate();
        $date = date_format($date, "Y-m-d");
        $dates["$date"] = $date;
    }
    return $dates;
}

?>

