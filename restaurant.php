<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
require_once($root . "/Service/restaurantTypeLinkService.php");

$restaurantService = new restaurantService();
$restaurantTypeService = new restaurantTypeLinkService();


if (isset($_POST["gotooverview"])) {
    header('Location: food.php');
    exit();
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Food</title>
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

    <section class="container-fluid w-70">
        <?php
        if (isset($_GET["restaurantId"])) {
            $restaurantId = $_GET["restaurantId"];
            $restaurants = $restaurantService->getById($restaurantId);

            if ($restaurants != null) {
                echo "<section class='row' style='margin-top: 2%'>";
                if (is_array($restaurants)) {
                    foreach ($restaurants as $restaurant) {
                        echoRestaurant($restaurant);
                    }
                } else {
                    echoRestaurant($restaurants);
                }
            } else {
                echo "Could not get information about this restaurant.";
            }
        } else {
            header("Location: food.php");
        }
        function echoRestaurant($restaurant)
        {
        echo "<section class='col-4 box'>";
        echo "<section class='col-12 text-center'>";
        $restaurantName = $restaurant->getName();
        $restaurantId = $restaurant->getId();
        $location = $restaurant->getLocation()->getAddress() . ", " . $restaurant->getLocation()->getPostalCode();
        $description = $restaurant->getDescription();
        $parking = $restaurant->getParking();
        $price = $restaurant->getPrice();
        $childrenPrice = $restaurant->getPrice() / 2;
        $stars = $restaurant->getStars();
        $seats = $restaurant->getSeats();

        $restaurantService = new restaurantService();
        $times = $restaurantService->getTimesByRestaurantId($restaurantId);
        $dates = $restaurantService->getDatesByRestaurantId($restaurantId);

        // Contact
        $phoneNumber = $restaurant->getPhoneNumber();
        $website = $restaurant->getWebsite();
        $contactpage = $restaurant->getContact();
        $menu = $restaurant->getMenu();

        echo "<h3 id='starsHeader'>$restaurantName</h3>";
        for ($x = 0; $x < $stars; $x++) {
            echo "<img class='stars' src='/img/icons/star.png' alt='ster'>";
        }
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Description:</p><p>{$description}</p></section>";

        if (!empty($parking)) {
            echo "<section class='row'><p style='color: orange; font-weight: bold'>Parking:</p><p>{$parking}</p></section>";
        }

        // PRICE
        echo "<img class='imgIcons' src='/img/icons/costs.png' alt='costs'>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Costs:</p>
<p><i>Diner costs</i></p>
<p>€{$price} p.p.</p>
<sup>Children til 12 years: € " . $childrenPrice . ",- p.p.</sup>

<p><i>Reservation costs</i></p>
<p>€ 10,- </p>
</section>";

        // SESSIONS
        echo "<img class='imgIcons' src='/img/icons/clockb.png'>";
        echo "<section class='row'><p class='infotext' style='color: orange; font-weight: bold'>Sessions:</p>";
        foreach ($times as $beginTime => $endTime) {
            echo "<p class='infotext'>$beginTime-$endTime</p>";
        }

        // DAYS
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Days:</p>";
        foreach ($dates as $date) {
            echo "<p>$date</p>";
        }

        echo "</section>";

        // LOCATION
        echo "<img class='imgIcons' src='/img/icons/location.png' alt='location'>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Address:</p><p>{$location}</p></section>";

        if (!empty($website)) {
            echo "<img class='imgIcons' src='/img/icons/links.png'>";
            echo "<section class='row'><p style='color: orange; font-weight: bold'>Links:</p><p>
<a href=\"$website\">Website</a><br>
<a href=\"$menu\">Menu</a><br>
<a href=\"$contactpage\">Contact</a>
</p></section>";
        }
        ?>
        <form method="post">
            <input type="submit" class='btn btn-primary w-100' name="gotooverview" value="Go back to overview"></input>
        </form>

        <?php
        echo "<form method=\"post\" action=\"foodreservation.php\">";
        echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
        ?>
        <input type="submit" class='btn btn-primary' name="makereservation" value="Make a reservation"></input>
        </form>
    </section>
    </section>
    </section>
    </section>

    <?php
    }

    ?>

</main>
</body>

</html>