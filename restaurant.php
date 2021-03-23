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
    <title>Restaurant - Haarlem Festival</title>
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
    <section>
        <?php
        if (isset($_GET["restaurantId"])) {
            try {
                $restaurantId = $_GET["restaurantId"];

                if (!intval($restaurantId)) {
                    throw new Exception("Invalid id in addressbar.");
                }
                $restaurant = $restaurantService->getById($restaurantId);

                if ($restaurant != null) {
                    echoRes($restaurant);
                    ?>
                    <form method="post" action="foodreservation.php">
                        <input name="restaurantId" type="hidden" value="<?php echo $restaurantId ?>">
                        <input type="submit" class='btn button1' id="buttonReservation" name="makereservation"
                               value="Make a reservation"></input>
                    </form>
                    <form method="post" action=food.php>
                        <input type="submit" class='btn button1' id="buttonOverview" name="gotooverview"
                               value="Go back to overview"></input>
                    </form>
                    <?php
                } else {
                    throw new Exception("Could not get information about this restaurant.");
                }
            } catch (Exception $exception) {
                $excMessage = $exception->getMessage();
                ?>
                <h1 class="header1Left">Restaurant not found</h1>
                <p><?php echo $excMessage ?></p>
                <?php
            }
        }

        function echoRes($restaurant)
        {
            $restaurantName = $restaurant->getName();
            $description = $restaurant->getDescription();
            $stars = $restaurant->getStars();
            $restaurantId = $restaurant->getId();
            $location = $restaurant->getLocation()->getAddress() . ", " . $restaurant->getLocation()->getPostalCode();
            $parking = $restaurant->getParking();
            $price = $restaurant->getPrice();
            $childrenPrice = $restaurant->getPrice() / 2;

            $seats = $restaurant->getSeats();

            $restaurantService = new restaurantService();
            $times = $restaurantService->getTimesByRestaurantId($restaurantId);
            $dates = $restaurantService->getDatesByRestaurantId($restaurantId);

            // Contact
            $phoneNumber = $restaurant->getPhoneNumber();
            $website = $restaurant->getWebsite();
            $contactpage = $restaurant->getContact();
            $menu = $restaurant->getMenu();
            ?>

            <section class="container">
                <section class="restaurantContentDescription">
                    <h2 id='starsHeader'><?php echo $restaurantName ?></h2>
                    <?php
                    for ($x = 0; $x < $stars; $x++) {
                        echo "<img class='stars' src='/img/icons/star.png' alt='ster'>";
                    }

                    if (!empty($description)) {
                        ?>

                        <p> <?php echo $description ?></p>

                        <?php
                    }

                    if (!empty($parking)) {
                        ?>
                        <br><h3>Parking information</h3>
                        <p><?php echo $parking ?></p>
                        <?php
                    }
                    ?>
                    <br>
                    <p><img src="/img/Restaurants/restaurant<?php echo $restaurantId ?>.png"
                            alt="Photo of <?php echo $restaurantName ?>" width="300"></p>
                </section>
                <section class="restaurantContentInfoPictureCosts">
                    <img class="imgIcons" src="/img/icons/costs.png" alt="costs">
                </section>
                <section class="restaurantContentInfoCosts">
                    <h3 class="informationRestaurantLabel">Costs</h3>
                    <?php
                    if (!empty($price) && !empty($childrenPrice)) {
                        ?>
                        <p><i>Diner costs</i></p>
                        <p>€<?php echo $price ?> p.p.</p>
                        <sup>Children til 12 years: € <?php echo $childrenPrice ?> p.p.</sup>

                        <p><i>Reservation costs</i></p>
                        <p>€10 </p>
                        <?php
                    } else {
                        ?>
                        <p>Could not get information about the costs.</p>
                        <?php
                    }
                    ?>
                </section>
                <section class="restaurantContentInfoPictureTime">
                    <img class="imgIcons" src="/img/icons/clockb.png" alt="clock">
                </section>
                <section class="restaurantContentInfoSessions">
                    <h3 class="informationRestaurantLabel">Sessions</h3>
                    <?php

                    if ($times == null || count($times) == 0) {
                        echo "<p>There are no times at this restaurant.</p>";
                    } else {
                        foreach ($times as $beginTime => $endTime) {
                            echo "<p class='infotext'>$beginTime-$endTime</p>";
                        }
                    }
                    ?>
                </section>
                <section class="restaurantContentInfoDays">
                    <h3 class="informationRestaurantLabel">Days</h3>
                    <?php
                    if ($dates == null || count($dates) == 0) {
                        echo "<p>There are no dates at this restaurant.</p>";
                    } else {
                        foreach ($dates as $date) {
                            echo "<p>$date</p>";
                        }
                    }
                    ?>
                </section>
                <section class="restaurantContentInfoPictureLocation">
                    <p><img class="imgIcons" src="/img/icons/location.png" alt="location"></p>
                </section>
                <section class="restaurantContentInfoAddress">
                    <h3 class="informationRestaurantLabel">Location</h3>
                    <?php
                    if (isset($location)) {
                        ?>
                        <p><?php echo $location ?> Haarlem</p>
                        <?php
                    } else {
                        ?>
                        <p>No location information about this restaurant.</p>
                        <?php
                    }
                    ?>
                </section>
                <section class="restaurantContentInfoPictureLinks">
                    <img class="imgIcons" id="restaurantContentInfoPictureLinks" src="/img/icons/links.png" alt="links">
                </section>
                <section class="restaurantContentInfoLinks">
                    <?php
                    if (!empty($website)) {
                        ?>
                        <h3 class="informationRestaurantLabel">Location</h3>
                        <a href="<?php echo $website ?>">Website</a><br>
                        <a href="<?php echo $menu ?>">Menu</a><br>
                        <a href="<?php echo $contactpage ?>">Contact</a>
                    <?php
                    }
                    else{
                    ?>
                        <script>
                            var link = document.getElementById('restaurantContentInfoPictureLinks');
                            link.style.display = 'none'; //or
                            link.style.visibility = 'hidden';
                        </script>
                        <?php
                    }
                    ?>
                </section>
            </section>
            <?php
        }

        ?>
    </section>
</main>
</body>

</html>