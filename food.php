<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>


<header>
    <div class="title">
        <h1 class="main-title">Food</h1>
        <p class="main-title under-title">Haarlem has several restaurants in the center.
            <br>
            We have selected some of these restaurants for you and give you a great experience.
        </p>
    </div>
</header>

<main class="content">

    <p>We have selected a few restaurants for you. At these restaurants you can enjoy delicious food at a competitive
        price.
        You pay € 10.00 per person in advance in reservation costs.
        In addition, children under the age of twelve receive a 50% discount on their dinner.
    </p>
    <h1>Our restaurants</h1>


    <section>
        <section id="filterbar">
            <section class="stars">
                <p class="filterlabelSubtitle">Stars</p>
                <section class="checkboxesStars">
                    <input type="checkbox" class="filterCheckbox" id="3stars" name="3stars" checked>
                    <label class="label" for="3stars">3 stars<br></label>
                    <input type="checkbox" class="filterCheckbox" id="4stars" name="4stars" checked>
                    <label class="label" for="4stars">4 stars</label>
                </section>
            </section>

            <section class="cuisine">
                <p class="filterlabelSubtitle">Cuisine</p>

                <select name="cuisine" id="cuisine">
                    <option value="1">Argentinian</option>
                    <option value="2">Dutch</option>
                    <option value="3">European</option>
                    <option value="4">Fish</option>
                    <option value="5">French</option>
                </select>
            </section>

            <section class="searchbar">
                <p class="filterlabelSubtitle"><br>Search for a restaurant</p>
                <form action="/action_page.php">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit" class="button1">Search</button>
                </form>
            </section>
        </section>
    </section>


    <section class="w3-container">
        <section id="id01" class="w3-modal">
            <section class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
                <form class="w3-container" action="/action_page.php">

                    <h1>Reservation Restaurant Fris</h1>

                    <section class="reservationsection">
                        <label><b>Amount of seats</b></label>

                        <select name="seats" id="seats">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </section>

                    <section class="reservationsection">
                        <label class="labelTitle">Date<br></label>
                        <input type="radio" class="date" name="date" id="date1" value="1">
                        <label for="date1">17:30 - 19:00</label><br>
                        <input type="radio" id="date" name="date" id="date2" value="2">
                        <label for="date2">19:00 - 20:30</label><br>
                        <input type="radio" id="date" name="date" id="session3" value="3">
                        <label for="date3">20:30 - 22:00</label><br><br>
                    </section>

                    <br>
                    <section class="reservationsection">
                        <label class="labelTitle">Session<br></label>
                        <input type="radio" class="session" name="session" id="session1" value="1">
                        <label for="session1">17:30 - 19:00</label><br>
                        <input type="radio" id="session" name="session" id="session2" value="2">
                        <label for="session2">19:00 - 20:30</label><br>
                        <input type="radio" id="session" name="session" id="session3" value="3">
                        <label for="session3">20:30 - 22:00</label><br><br>
                    </section>

                    <section class="reservationsection">
                        <label class="labelTitle">Note</label>
                        <p>Do you have any dietary requirements, allergies or other comments?</p>
                        <textarea id="noteTextArea" rows="3"></textarea>
                    </section>


                    <section class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                        <button onclick="document.getElementById('id01').style.display='none'" type="button"
                                class="w3-button w3-red">Cancel
                        </button>
                        <input class="w3-button w3-green w3-right w3-padding" type="submit" name="reservation"
                               id="session3" value="Send">
                    </section>
                </form>

            </section>
        </section>
    </section>


    <section class="callout">
        <section class="closebtn" onclick="this.parentElement.style.display='none';">×</section>
        <section class="callout-container">
            <h1>Berichtgeving hier</h1>
            <p>Berichtgeving hier</p>
        </section>
    </section>

    <section class="container-fluid w-70">
        <?php
        $restaurantService = new restaurantService();

        $format = "HH:MM";

        $restaurants = $restaurantService->getAll();


        echo "<section class='row' style='margin-top: 2%'>";

        foreach ($restaurants

        as $restaurant) {

        echo "<section class='col-4 box'>";
        echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";
        $restaurantName = $restaurant->getName();
        $restaurantId = $restaurant->getId();
        echo $restaurantId . " ID";
        $location = $restaurant->getLocation()->getAddress() . " " . $restaurant->getLocation()->getPostalCode();
        $description = $restaurant->getDescription();
        $price = "€" . $restaurant->getPrice() . ",-";

        echo "<p style='color: orange; font-weight: bold'>{$restaurantName}</p>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Location:</p><bold>{$location}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Session:</p><bold>{$description}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";

        echo "<form method=\"POST\">";
        echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
        ?>
        <input type="submit" class='btn btn-primary' name="moreinformation" value="More information"></input>
        <input type="submit" class='btn btn-primary' name="makereservation" value="Make a reservation"></input>
        </form>
    </section>
    </section>

    <?php
    }
    echo "</section>";
    ?>
    </section>

    <?php
    if (isset($_POST["restaurantId"])) {
        $restaurantId = $_POST["restaurantId"];

        echo "RESTAURANT ID IS $restaurantId";

        ?>
        <script>
            document.getElementById('id01').style.display = 'block';
        </script>

        <?php
    }
    ?>

</main>
</body>

</html>