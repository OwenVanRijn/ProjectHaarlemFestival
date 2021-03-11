<?php
    require_once "Service/artistOnActivityService.php";
    require_once "UI/navBar.php";
    
    $name = (string)$_GET["name"];
    $nameStripped = strtolower(str_replace(' ', '', $name));

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
            <section class="row h-100 justify-content-center align-self-center">
                <section class="col-3" style="padding: 0;">
                    <?php
                       echo "<img src='img/Artists/bw/{$nameStripped}.png' class='w-100' alt='{$name}'>";
                    ?>
                </section>
                <section class="col-9 text-center" style="background-color: gray">
                    <h1><?php echo $name ?></h1>
                </section>
            </section>
        </header>

        <section class="container-fluid">
            <a href="dance.php" style="color:#FD6A02"><strong>← Back to dance overview</strong></a>
            <?php
                echo "<section class='row text-center' style='margin-top: 2%'>";

                $service = new artistOnActivityService();
                $artistActivity = $service->getActivityByArtist($name);

                foreach ($artistActivity as $item){

                    echo "<section class='col-4 box'>";
                    echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";

                    $date = date_format($item->getActivity()->getActivity()->getDate(), "d-M");
                    $time = $item->getActivity()->getActivity()->getStartTime()->format("H:i");

                    $location = $item->getActivity()->getActivity()->getLocation()->getName();

                    $session = $item->getActivity()->getType();

                    $price = "€".$item->getActivity()->getActivity()->getPrice().",-";

                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Date:</p>{$date}</section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Start time:</p>{$time}</section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Location:</p><bold>{$location}</bold></section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Session:</p><bold>{$session}</bold></section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";
                    echo "</section>";
                    echo "<a href='#' class='btn btn-primary w-100'>Add to cart</a>";
                    echo "</section>";
                }


                echo "<section class='row justify-content-center align-self-center text-center' style='margin: 2%'>";
                echo "<section class= 'row justify-content-center align-self-center text-center'>";
                echo "<h2>Get to know the artist:</h2>";
                echo "</section>";

                echo "<section class='row justify-content-center align-self-center text-center'>";
                echo"<section class='col-8'>";
                echo $artistActivity[0]->getArtist()->getDescription();
                echo"</section>";
                echo "</section>";
                echo "</section>";

            echo "</section>";
            ?>
            <section class='row justify-content-center align-self-center text-center' style='margin: 2%'>
                <section class="col-6">
                    <section class='col-12 text-center' style='background-color: #9A9999; color: black;'>
                        <section class='row justify-content-center align-self-center text-center h-100' style='background-color: #FD6A02;'>
                            <h3>For the dance kids / teens</h3>";
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
        </section>
    </body>

</html>