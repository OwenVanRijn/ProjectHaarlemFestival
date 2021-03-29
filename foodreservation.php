<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
require_once($root . "/Service/shoppingcartService.php");

$foodactivityService = new foodactivityService();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Reservation - Haarlem Festival</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/food.css">
    <meta charset="UTF-8">
    <meta name="keywords"
          content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
<?php
require_once("UI/navBar.php");
?>

<section class="content">
    <section id="id01" class="w3-modal">

        <h1 class="header1Left">Make a reservation</h1>
        <?php

        try {
            if (isset($_POST["reservation"])) {
                if (isset($_POST["session"]) && isset($_POST["date"]) && isset($_POST["seats"])) {
                    $restaurantId = $_POST["restaurantId"];
                    $seats = $_POST["seats"];
                    $session = $_POST["session"];
                    $date = $_POST["date"];

                    try {
                        $times = explode("-", $session);
                        $foodactivity = $foodactivityService->getBySessionDate($date, $times, $restaurantId);
                        if ($foodactivity == NULL) {
                            throw new Exception("Could not find a valid activity. Please choose a valid date and session.");
                        } else {
                            $shoppingcartService = new shoppingcartService();
                            $shoppingcartService->getShoppingcart()->addToShoppingcartItemsById($foodactivity->getActivity()->getId(), $seats);

                            header("Location: food.php", true, 301);
                            $_SESSION["foodreservationName"] = $foodactivity->getRestaurant()->getName();
                            exit();
                        }
                    } catch (Exception $exception) {
                        throw new Exception("{$exception->getMessage()}");
                    }
                } else {
                    throw new Exception("Please select an amount of seats, session and date.");
                }
            }
        } catch (Exception $exception) {
            $excMessage = $exception->getMessage();
            echo "<p style='color: red'>$excMessage</p>";
        }


        if (!isset($_POST["restaurantId"])) {
            header("Location: food.php", true, 301);
            exit();
        } else {
            $restaurantId = $_POST["restaurantId"];
        }
        try {
            if (!is_numeric($restaurantId)) {
                throw new Exception("Could not find the eventinformation by this restaurant. Restaurant ID $restaurantId is not a valid ID.");
            }
            $foodactivities = $foodactivityService->getByRestaurantId($restaurantId);
            if ($foodactivities == null) {
                throw new Exception("Could not find the eventinformation by this restaurant.");
            } else {
                if (!isset($foodactivities[0])) {
                    throw new Exception("Could not find an restaurant.");
                }
                if (!($foodactivities[0]->getActivity())) {
                    throw new Exception("Could not find an activity.");
                }
                ?>


                <form class="" method="post">
                    <section class="reservationsection">
                        <section class="containerReservation">
                            <section class="restaurantReservationCLRestaurantLabel">
                                <h2 id="restaurantNameReservation">Reservation
                                    at <?php echo $foodactivities[0]->getRestaurant()->getName() ?></h2>
                            </section>


                            <section class="restaurantReservationCLCostsTitle">
                                <section id="costsBox">
                                    <p class="confirmationBoxTitle" id="confirmationBoxTitleCosts">Costs</p>
                                </section>
                            </section>

                            <section class="restaurantReservationCISeatsTitle">
                                <h3 class="labelTitle">Amount of seats</h3>
                            </section>

                            <section class="restaurantReservationCISeatsCB">
                                <select name="seats" id="seatsCb" onchange="seatsToScreen()">
                                    <option value="1">1 seat</option>
                                    <option value="2">2 seats</option>
                                    <option value="3">3 seats</option>
                                    <option value="4">4 seats</option>
                                    <option value="5">5 seats</option>
                                </select>
                            </section>


                            <section class="restaurantReservationCLReservationcosts">
                                <section class="box confirmationBoxReservation">
                                    <p class="confirmationBoxSubtitle">Reservationcosts</p>
                                    <p class="confirmationBoxText">
                                        €<?php echo $foodactivities[0]->getActivity()->getPrice() ?></p>
                                </section>
                            </section>
                            <section class="restaurantReservationCLAmount">
                                <section class="box confirmationBoxAmount">
                                    <p class="confirmationBoxSubtitle">Amount</p>
                                    <p id="seatsLabel" class="confirmationBoxText">1</p>
                                </section>
                            </section>
                            <section class="restaurantReservationCLTotal">
                                <section class="box confirmationBoxTotal">
                                    <p class="confirmationBoxTitle">Total</p>
                                    <p id="totalLabel"
                                       class="confirmationBoxText">
                                        €<?php echo $foodactivities[0]->getActivity()->getPrice() ?></p>
                                </section>
                            </section>
                            <section class="restaurantReservationCIDays">
                                <section class="reservationsection">
                                    <h3 class="labelTitle">Date</h3>
                                    <?php
                                    $index = 1;

                                    $restaurantService = new restaurantService();
                                    $dates = $restaurantService->getDates($foodactivities);

                                    foreach ($dates as $date) {
                                        echo "<input type=\"radio\" class=\"date\" name=\"date\" id=\"date$index\" value=\"$date\" required onchange='dateToScreen(this.value)'>";
                                        echo "<label for=\"date$index\">$date</label><br>";
                                        $index++;
                                    }
                                    ?>
                                </section>
                            </section>
                            <section class="restaurantReservationCISessions">
                                <section class="reservationsection">
                                    <h3 class="labelTitle">Session</h3>
                                    <?php
                                    $index = 1;

                                    $restaurantService = new restaurantService();
                                    $times = $restaurantService->getTimes($foodactivities);

                                    foreach ($times as $startTimeStr => $endTimeStr) {
                                        echo "<input type=\"radio\" class=\"session\" name=\"session\" id=\"session$index\" value=\"$startTimeStr-$endTimeStr\" required onchange='sessionToScreen(this.value)'>";
                                        echo "<label for=\"session$index\">$startTimeStr - $endTimeStr</label><br>";
                                        $index++;
                                    }
                                    ?>
                                </section>
                            </section>


                            <section class="restaurantReservationCLDays">
                                <section class="box confirmationBoxDate">
                                    <p class="confirmationBoxTitle">Date</p>
                                    <p id="dateLabel" class="confirmationBoxText">-</p>
                                </section>
                            </section>
                            <section class="restaurantReservationCLSession">
                                <section class="box confirmationBoxSession">
                                    <p class="confirmationBoxTitle">Session</p>
                                    <p id="sessionLabel" class="confirmationBoxText">-</p>
                                </section>
                            </section>
                            <section class="restaurantReservationCINote">
                                <section class="reservationnote">
                                    <h3 class="labelTitle">Note</h3>
                                    <p>Do you have any dietary requirements, allergies or other comments?</p>
                                    <textarea id="noteTextArea" rows="5" name="notes"
                                              onchange="noteToScreen()"></textarea>
                                </section>
                            </section>
                            <section class="restaurantReservationCLYourNote">
                                <section class="box confirmationBoxNote">
                                    <p class="confirmationBoxTitle">Note</p>
                                    <p id="noteLabel" class="confirmationBoxText">-</p>
                                </section>
                            </section>
                            <section class="restaurantReservationCLSideNote">
                                <p id="noteSubNoteLabel">Note: You have to pay €10,00 p.p. at checkout for reservation
                                    costs</p>
                            </section>
                        </section>
                    </section>

                    <section class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                        <input type="button" class="btn button1 w-100" onclick="location.href='food.php'"
                               value="Cancel"/>
                        <?php
                        echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
                        ?>
                        <input disabled class="btn button1 w-100" type="submit" name="reservation" id="makeareservation"
                               value="Make a reservation">
                    </section>


                </form>

                <?php
            }
        } catch (Exception $exception) {
            $excMessage = $exception->getMessage();
            ?>
            <h2>Restaurant eventinformation not found</h2>
            <p><?php echo $excMessage ?></p>
            <br>
            <button class="btn button1 w-100" onclick="location.href='food.php'">Go back to the foodpage</button>
            <button class="btn button1 w-100" onclick="location.href='contact.php'">Make contact with our team</button>
            <?php
        }

        ?>


    </section>
</section>

</body>
</html>

<script>
    function seatsToScreen() {
        var seats = document.getElementById("seatsCb");
        document.getElementById("seatsLabel").innerHTML = seats.value;

        var price = "<?php echo $foodactivities[0]->getActivity()->getPrice()?>" * seats.value;
        document.getElementById("totalLabel").innerHTML = '€' + price;

        checkMakeAReservationButton();
    }

    function sessionToScreen(session) {
        document.getElementById("sessionLabel").innerHTML = session;

        checkMakeAReservationButton();
    }

    function dateToScreen(date) {
        document.getElementById("dateLabel").innerHTML = date;

        checkMakeAReservationButton();
    }

    function noteToScreen() {
        var note = document.getElementById("noteTextArea");
        if (note.value == "") {
            document.getElementById("noteLabel").innerHTML = "-";
        } else {
            document.getElementById("noteLabel").innerHTML = note.value;
        }

        checkMakeAReservationButton();
    }

    function checkMakeAReservationButton() {
        if (document.getElementById("dateLabel").innerHTML == "-" ||
            document.getElementById("sessionLabel").innerHTML == "-"
        ) {
            document.getElementById("makeareservation").disabled = true;
        } else {
            document.getElementById("makeareservation").disabled = false;
        }
    }
</script>