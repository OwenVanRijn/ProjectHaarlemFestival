<?php
    require_once "Service/artistOnActivityService.php";
    require_once "Service/danceActivityService.php";
    require_once "Service/danceArtistService.php";
    require_once "Service/shoppingcartService.php";
    require_once "UI/navBar.php";

    $shoppingCartService = new shoppingcartService();
    $danceService = new danceActivityService();

    $name = (string)$_GET["name"];
    $nameStripped = strtolower(str_replace(' ', '', $name));

    if(isset($_POST['select'])){
        $id = $_POST['selectedId'];

        $activity = $danceService->getActivityFromId($id);

        $id = $activity->getActivity()->getId();

        $shoppingCartService->getShoppingcart()->setShoppingcartItemById($id, 1);
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Hardwell - Haarlem Festival</title>
        <link rel="stylesheet" href="css/dance.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css";
        <meta charset="UTF-8">
        <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
        <meta name="description" content="Haarlem Festival">
        <meta name="author" content="Haarlem Festival">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <header>
            <section class="row" style="background-color: #666666; margin-top: 3em;">
                <section class="col-1">
                    <img src='img/Artists/bw/<?php echo $nameStripped?>.png' class="w-80">
                </section>
                <section class="col-11 text-center" style="margin-top: 3em;">
                    <h1><?php echo $name ?></h1>
                </section>
            </section>
        </header>

        <section class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <a href="dance.php" style="color:#FD6A02"><strong>← Back to dance overview</strong></a>

                    <?php
                    echo "<section class='row text-center' style='margin-top: 2%'>";

                    $service = new artistOnActivityService();
                    $artistActivity = $service->getActivityByArtist($name);

                    foreach ($artistActivity as $item){

                        echo "<section class='col-4 box'>";
                        echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";

                        $artiststrarray = $item->getArtists();
                        $artists = "";

                        foreach ($artiststrarray as $artist) {
                            $artists .= $artist->getName() . " ";
                        }

                        $id = $item->getActivity()->getId();
                        $date = date_format($item->getActivity()->getDate(), "d-M");
                        $time = $item->getActivity()->getStartTime()->format("H:i");

                        $location = $item->getActivity()->getLocation()->getName();

                        $session = $item->getType();

                        $price = "€".$item->getActivity()->getPrice().",-";

                        echo "<p style='color: orange; font-weight: bold'>{$artists}</p>";
                        echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Date:</p>{$date}</section>";
                        echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Start time:</p>{$time}</section>";
                        echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Location:</p><bold>{$location}</bold></section>";
                        echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Session:</p><bold>{$session}</bold></section>";
                        echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";
                        echo "</section>";
                        echo "<button class='btn btn-primary w-100' type='submit' name='select' value='{$id}'>Add to cart</a>";
                        echo "</section>";
                    }
                    ?>
            </form>

            <section class='row justify-content-center align-self-center text-center' style='margin: 2%'>
                    <section class= 'row justify-content-center align-self-center text-center'>
                        <h2>Get to know the artist:</h2>
                    </section>

                    <section class='row justify-content-center align-self-center text-center'>
                        <section class='col-8'>
                         <?php $artistService = new danceArtistService();
                         $artist = $artistService->getFromName($_GET['name']);

                         echo $artist->getDescription();?>
                        </section>
                    </section>
            </section>

            <section class='row justify-content-center align-self-center text-center' style='margin: 2%'>
                <section class="col-6">
                    <section class='col-12 text-center' style='background-color: #9A9999; color: black;'>
                        <section class='row justify-content-center align-self-center text-center' style='background-color: #FD6A02;'>
                            <h3>For the dance kids / teens</h3>
                        </section>
                        <section class='row justify-content-center align-self-center' style='margin: 2%'>
                           <p>There's a silent disco going on at Inholland Hogeschool from
                                14:00 to 24:00. Free drinks and food included!
                                Free walk-in and no entry cost!<br><br>

                                Hogeschool Inholland - Bijdorplaan 15  - SUCH building</p>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </body>

</html>