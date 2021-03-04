<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
require_once($root . "/Service/restaurantTypeService.php");

$restaurantService = new restaurantService();
$restaurantTypeService = new restaurantTypeService();

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
                <form>
                    <select name="cuisine" id="cuisine" onchange="this.form.submit()">
                        <option value="1">Argentinian</option>
                        <option value="2">Dutch</option>
                        <option value="3">European</option>
                        <option value="4">Fish</option>
                        <option value="5">French</option>
                    </select>
                </form>
            </section>

            <section class="searchbar">
                <p class="filterlabelSubtitle"><br>Search for a restaurant</p>
                <form method="post">
                    <input type="text" placeholder="Search.." name="searchterm">
                    <button type="submit" class="button1" name="searchbutton">Search</button>
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
        $format = "HH:MM";


        if (isset($_POST["searchbutton"])) {

            $searchTerm = $_POST["searchterm"];

            $stars3 = "false";
            $stars4 = "false";
            $restaurants = $restaurantService->getBySearch($searchTerm, $stars3, $stars4);
        } else if (isset($_GET["cuisine"])) {
            $cuisine = $_GET["cuisine"];
            $restaurants = $restaurantService->getByType($cuisine);
        } else {
            $restaurants = $restaurantService->getAll();
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

        if (isset($_POST["restaurantId"])) {
            $restaurantId = $_POST["restaurantId"];

            echo "RESTAURANT ID IS $restaurantId";

            ?>
            <script>
                document.getElementById('id01').style.display = 'block';
            </script>

            <?php
        }

        if (isset($_POST["seats"]) && isset($_POST["date"]) && isset($_POST["session"]) && isset($_POST["note"])) {

            $seats = $_POST["seats"];
            $date = $_POST["date"];
            $session = $_POST["session"];
            $note = $_POST["note"];
            $restaurantId = $_POST["restaurantId"];


            $foodActivityService = new foodactivityService();
            $foodActivityService->getAll([
                "activity.type" => new dbContains("Food"),
                "activity.date" => new dbContains($date),
                "foodActivity.restaurantId" => new dbContains($restaurantId)
            ]);

            //$danceThing = new artistOnActivityDAO();
            //print_r($danceThing->get([
            //    "danceartist.name" => new dbContains("Afro")
            //]));


            $shoppingcartService = new shoppingcartService();
            $shoppingcartService->getShoppingcart()->addToShoppingcartItemsById($activityId, $seats);


        }


        function echoRestaurant($restaurant)
        {
        echo "<section class='col-4 box'>";
        echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";
        $restaurantName = $restaurant->getName();
        echo $restaurantName;
        $restaurantId = $restaurant->getId();
        echo $restaurantId . " ID";
        $location = $restaurant->getLocation()->getAddress() . " " . $restaurant->getLocation()->getPostalCode();
        $description = $restaurant->getDescription();
        $price = "€" . $restaurant->getPrice() . ",-";

        echo "<p style='color: orange; font-weight: bold'>{$restaurantName}</p>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Location:</p><bold>{$location}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Session:</p><bold>{$description}</bold></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";

        echo "<form method=\"POST\" action=\"restaurant.php\">";
        echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
        ?>
        <input type="submit" class='btn btn-primary' name="moreinformation" value="More information"></input>
        </form>

        <?php
        echo "<form method=\"POST\" action=\"foodreservation.php\">";
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