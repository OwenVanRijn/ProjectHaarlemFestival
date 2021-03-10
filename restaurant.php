<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
require_once($root . "/Service/restaurantTypeLinkService.php");

$restaurantService = new restaurantService();
$restaurantTypeService = new restaurantTypeLinkService();

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

    <section class="container-fluid w-70">
        <?php
        if (isset($_GET["restaurantId"])) {
            $restaurantId = $_GET["restaurantId"];
            $restaurants = $restaurantService->getById($restaurantId);
        }

        echo "<section class='row' style='margin-top: 2%'>";
        if ($restaurants != null) {
            if (is_array($restaurants)) {
                foreach ($restaurants as $restaurant) {
                    echoRestaurant($restaurant);
                }
            } else {
                echoRestaurant($restaurants);
            }
        } else {
            echo "No results here.";
        }

        function echoRestaurant($restaurant)
        {
        echo "<section class='col-4 box'>";
        echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";
        $restaurantName = $restaurant->getName();
        echo $restaurantName;
        $restaurantId = $restaurant->getId();
        echo $restaurantId . " ID";
        $location = $restaurant->getLocation()->getAddress() . ", " . $restaurant->getLocation()->getPostalCode();
        $description = $restaurant->getDescription();
        $price = "€" . $restaurant->getPrice() . ",-";
        $childrenPrice = "€" . $restaurant->getPrice() / 2 . ",-";
        $stars = $restaurant->getStars();
        $seats = $restaurant->getSeats();
        $phoneNumber = $restaurant->getPhoneNumber();


        echo "<section class='row'><p style='color: orange; font-weight: bold'>Description:</p><bold>{$restaurantName}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Description:</p><bold>{$description}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Address:</p><bold>{$location}</bold></section>";
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