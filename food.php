<!DOCTYPE html>
<html>

<head>
    <title>Food - Haarlem Festival</title>
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
session_start();
require_once("Service/foodactivityService.php");
require_once("Service/restaurantService.php");
require_once("Service/restaurantTypeLinkService.php");

$restaurantService = new restaurantService();
$restaurantTypeLinkService = new restaurantTypeLinkService();
?>
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

    <p style="text-align: center">
        We have selected a few restaurants for you. At these restaurants you can enjoy delicious food at a competitive
        price.
        <br>You pay €10,00 per person in advance in reservation costs.
        <br>In addition, children under the age of twelve receive a 50% discount on their dinner.
    </p>
</main>
<h1>Our restaurants</h1>
<section id="filterbarFood">

    <section class="grid-container" id="filterbarFoodGrid">
        <section class="starscheck">
            <p class="filterlabelSubtitle">Stars</p>
            <form method="post">
                <section class="checkboxesStars">
                    <input type="checkbox" class="filterCheckbox" id="stars3" name="stars3" checked
                           onclick="this.form.submit()">
                    <label class="label" for="stars3">3 stars</label><br>
                    <input type="checkbox" class="filterCheckbox" id="stars4" name="stars4" checked
                           onclick="this.form.submit()">
                    <label class="label" for="stars4">4 stars</label><br>
                </section>
            </form>
        </section>
        <section class="cuisine">
            <p class="filterlabelSubtitle">Cuisine</p>
            <form method="post">
                <select name="cuisine" id="cuisine" onchange="this.form.submit()">

                    //Vul de dropdown met alle keukens
                    <?php
                    $restaurantTypes = $restaurantTypeLinkService->getAllTypes();

                    echo "<option value=\"0\">All cuisines</option>";
                    foreach ($restaurantTypes as $restaurantType) {
                        $restaurantTypeName = $restaurantType->getName();
                        $restaurantTypeId = $restaurantType->getId();
                        echo "<option value=\"$restaurantTypeId\">$restaurantTypeName</option>";
                    }
                    ?>
                </select>
            </form>
        </section>
        <section class="searchbar">
            <p class="filterlabelSubtitle">Search for a restaurant</p>
            <form method="post">
                <section id="searchbarGroup">
                    <input type="text" placeholder="Search.." id="searchterm" name="searchterm">
                    <button type="submit" class="button1" name="searchbutton">Search</button>
                </section>
            </form>
        </section>
    </section>

</section>

<main class="content">

    <?php
    // Bekijk of een van de filters is toegepast
    try {
        if (isset($_POST["stars3"]) || isset($_POST["stars4"])) { // Filter op sterren
            // stars : 3
            if (isset($_POST["stars3"])) {
                $stars3 = true;
            } else {
                $stars3 = false;
                echo "<script>document.getElementById(\"stars3\").checked = false</script>";
            }

            // stars : 4
            if (isset($_POST["stars4"])) {
                $stars4 = true;
            } else {
                $stars4 = false;
                echo "<script>document.getElementById(\"stars4\").checked = false</script>";
            }

            try {
                $restaurants = $restaurantService->getByStars($stars3, $stars4);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        } else if (isset($_POST["cuisine"])) { // Filter op keuken
            $cuisine = $_POST["cuisine"];
            $restaurants = $restaurantTypeLinkService->getByType($cuisine);

            echo "<script>document.getElementById(\"cuisine\").value = \"$cuisine\";</script>";

            try {
                $restaurants = $restaurantTypeLinkService->getByType($cuisine);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        } else if (isset($_POST["searchbutton"])) {
            // Zoek op zoekterm
            if (isset($_POST["searchterm"])) {
                $searchTerm = $_POST["searchterm"];
                echo "<script>document.getElementById(\"searchterm\").value = '$searchTerm'</script>";
                $restaurants = $restaurantService->getBySearchTerm($searchTerm);
            } else {
                $searchTerm = "";
                $restaurants = $restaurantService->getAll();
            }
        } else {
            $restaurants = $restaurantService->getAll();
        }

        if ($restaurants != null) {
            if (is_array($restaurants)) {
                foreach ($restaurants as $restaurant) {
                    echoRestaurant($restaurant);
                }
            } else {
                echoRestaurant($restaurants);
            }
        } else {
            echo "<p>Could not find an restaurant.</p>";
        }
    } catch (Exception $exception) {
        ?>
        <h2>Kan de restaurants niet ophalen.</h2>
        <p><?php echo $exception->getMessage() ?></p>
        <?php
    }

    function getTimes($foodactivities)
    {
        // Stop alle tijden die het restaurant aanbied in een array.
        if ($foodactivities == null) {
            return null;
        }
        foreach ($foodactivities as $foodactivity) {
            $startTime = $foodactivity->getActivity()->getStartTime();
            $endTime = $foodactivity->getActivity()->getEndTime();
            $startTimeStr = date_format($startTime, 'H:i');
            $endTimeStr = date_format($endTime, 'H:i');

            $times["$startTimeStr"] = $endTimeStr;
        }
        return $times;
    }

    function echoRestaurant($restaurant)
    {
        // Haal alle informatie op over het restaurant
        $restaurantId = $restaurant->getId();
        $restaurantName = $restaurant->getName();
        $stars = $restaurant->getStars();
        $restaurantTypeLink = new restaurantTypeLinkService();
        $foodactivityService = new foodactivityService();
        $foodactivities = $foodactivityService->getByRestaurantId($restaurantId);


        $restaurantTypes = $restaurantTypeLink->getRestaurantTypes($restaurantId);
        $restaurantLocation = $restaurant->getLocation()->getAddress() . " " . $restaurant->getLocation()->getPostalCode();
        $restaurantPrice = "€" . $restaurant->getPrice();

        $restaurantService = new restaurantService();
        $times = $restaurantService->getTimes($foodactivities);
        ?>


        <section id="foodRestaurantContainer">
            <section class="containerFood">
                <section class="foodRestaurantName"><h3 class="restaurantNameFood"><?php echo $restaurantName ?></h3>
                </section>
                <section class="foodRestaurantPrice"><p><?php echo $restaurantPrice ?></p></section>
                <section class="foodRestaurantImage">
                    <img class='foodimg' src='img/Restaurants/restaurant<?php echo $restaurantId ?>.png'
                         alt='Photo of <?php echo $restaurantName ?>'>
                </section>
                <section class="foodRestaurantTypes"><p><?php echo implode(", ", $restaurantTypes); ?></p></section>
                <section class="foodRestaurantSession"><p><?php
                        if ($times != null) {
                            foreach ($times as $startTimeStr => $endTimeStr) {
                                echo "$startTimeStr - $endTimeStr<br>";
                            }
                        } else {
                            echo "No time information available.";
                        }
                        ?></p>
                </section>
                <section class="foodRestaurantLocation"><p><?php echo $restaurantLocation ?></p></section>
                <section class="foodRestaurantStars">
                    <?php
                    if ($stars != null) {
                        for ($x = 0; $x < $stars; $x++) {
                            echo "<img class='stars' src='/img/Icons/starw.png' alt='star'>";
                        }
                    }
                    ?>
                </section>
                <section class="foodRestaurantNote"></section>
            </section>
            <form method="GET" action="restaurant.php">
                <input name="restaurantId" type="hidden" value="<?php echo $restaurantId ?>">
                <input type="submit" class='button1 btnFood' value="More information">
            </form>
            <form method="POST" action="foodreservation.php">
                <input name="restaurantId" type="hidden" value="<?php echo $restaurantId ?>">
                <input type="submit" class='button1 btnFood' name="makereservation" value="Make a reservation">
            </form>
        </section>
        <?php
    }


    // Melding als er zojuist een reservering is gemaakt.
    if (isset($_SESSION["foodreservationName"])) {
        $restaurantName = $_SESSION["foodreservationName"];
        if (empty($restaurantName)) {
            $restaurantName = "a restaurant";
        }

        ?>
        <section class="callout" id="popupConfirmMessage">
            <section class="closebtn" onclick="this.parentElement.style.display='none';">×</section>
            <section class="callout-container">
                <h1 id="reservationCreated">Reservation created</h1>
                <p>Added your reservation at <?php echo $restaurantName ?> to the shoppingcart.</p>
            </section>
        </section>
        <?php
        unset($_SESSION["foodreservationName"]);
    }
    ?>
</main>
<?php
require_once "UI/footer.php";
?>
</body>
</html>