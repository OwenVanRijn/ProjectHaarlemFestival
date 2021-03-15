<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once($root . "/Service/foodactivityService.php");
require_once($root . "/Service/restaurantService.php");
require_once($root . "/Service/restaurantTypeLinkService.php");

$restaurantService = new restaurantService();
$restaurantTypeLinkService = new restaurantTypeLinkService();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Food</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    <p>We have selected a few restaurants for you. At these restaurants you can enjoy delicious food at a competitive
        price.
        You pay € 10.00 per person in advance in reservation costs.
        In addition, children under the age of twelve receive a 50% discount on their dinner.
    </p>
    <h1>Our restaurants</h1>


    <section>

        <section class="grid-container" id="filterbarFood">
            <section class="starscheck">
                <p class="filterlabelSubtitle">Stars</p>
                <form method="post">
                    <section class="checkboxesStars">
                        <input type="checkbox" class="filterCheckbox" id="stars3" name="stars3" checked onclick="this.form.submit()">
                        <label class="label" for="stars3">3 stars</label><br>
                        <input type="checkbox" class="filterCheckbox" id="stars4" name="stars4" checked onclick="this.form.submit()">
                        <label class="label" for="stars4">4 stars</label><br>
                    </section>
                </form>
            </section>
            <section class="cuisine">
                <p class="filterlabelSubtitle">Cuisine</p>
                <form method="post">
                    <select name="cuisine" id="cuisine" onchange="this.form.submit()">

                        <?php
                        $restaurantTypes = $restaurantTypeLinkService->getAllTypes();


                        usort($restaurantTypes, function ($a, $b) {
                            return strcmp($a->getName(), $b->getName());
                        });

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

    <section class="container-fluid w-70">
        <?php
        $format = "HH:MM";

        if (isset($_POST["stars3"]) || isset($_POST["stars4"])) {
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

            $restaurants = $restaurantService->getByStars($stars3, $stars4);
        } else if (isset($_POST["cuisine"])) {
            $cuisine = $_POST["cuisine"];
            $restaurants = $restaurantTypeLinkService->getByType($cuisine);

            echo "<script>document.getElementById(\"cuisine\").value = \"$cuisine\";</script>";

            $restaurants = $restaurantTypeLinkService->getByType($cuisine);
        } else if (isset($_POST["searchbutton"])) {
            // searchterm
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
            echo "<p>Could not find an restaurant.</p>";
        }

        if (isset($_POST["restaurantId"])) {
            $restaurantId = $_POST["restaurantId"];
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
        $restaurantId = $restaurant->getId();
        $restaurantName = $restaurant->getName();

        echo "<img class=\"foodimg\" src=\"img/Restaurants/restaurant$restaurantId.png\" alt=\"Photo of $restaurantName\">";
        echo "<br><h3 class='restaurantName' id='starsHeader'>$restaurantName</h3>";
        $stars = $restaurant->getStars();
        for ($x = 0; $x < $stars; $x++) {
            echo "<img class='stars' src='/img/icons/starw.png' alt='ster'>";
        }

        $location = $restaurant->getLocation()->getAddress() . " " . $restaurant->getLocation()->getPostalCode();
        $description = $restaurant->getDescription();
        $price = "€" . $restaurant->getPrice() . ",-";

        echo "<section class='row'><p style='color: orange; font-weight: bold'>Location:</p><p>{$location}</p></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Session:</p><p>{$description}</p></section>";
        echo "<section class='row'><p style='color: orange; font-weight: bold'>Price:</p><p>{$price}</p></section>";

        echo "<form method=\"GET\" action=\"restaurant.php\">";
        echo "<input name=\"restaurantId\" type=\"hidden\" value=\"$restaurantId\">";
        ?>
        <input type="submit" class='btn btn-primary' value="More information"></input>
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
    if (isset($_SESSION["foodreservationName"])) {
        var_dump($_SESSION["foodreservationName"]);

        $restaurantName = $_SESSION["foodreservationName"];
        if (empty($restaurantName)) {
            $restaurantName = "a restaurant";
        }

        ?>
        <section class="callout" id="popupConfirmMessage">
            <section class="closebtn" onclick="this.parentElement.style.display='none';">×</section>
            <section class="callout-container">
                <h1>Reservation created</h1>
                <p>Added your reservation at <?php echo $restaurantName ?> to the shoppingcart.</p>
            </section>
        </section>
        <?php
        unset($_SESSION["foodreservationName"]);
    }
    ?>

</main>
</body>

</html>