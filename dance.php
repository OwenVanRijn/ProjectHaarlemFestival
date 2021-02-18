<?php
require_once "Service/artistService.php";
require_once "Model/danceArtist.php";
require_once "Service/danceActivityService.php";
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Dance - Haarlem Festival</title>
        <link rel="stylesheet" href="css/style.css">
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
            <div class="title">
                <h1 class="main-title">Dance</h1>
                <p class="main-title under-title">Haarlem Festival, for the people that enjoy
                    <br>a good time
                </p>
            </div>
        </header>

        <section class="container h-100">
            <?php
                $service = new artistService();
                (array)$artistArray = $service->getArtists();

                //all-access
                echo "<section class='row h-100 align-items-center' style='background-color: #C0C0C0; margin: 2% 25% 2% 25%;
                      padding: 1% 0 1% 0;'>";
                echo "<section class='col-8 fonttickets'>All in ticket (Friday/Saturday/Sunday): €250,-</section>";
                echo "<section class='col-4 text-right'><button class='btn btn-primary'>Add Ticket</button></section>";
                echo "</section>";

                //Lineup
                echo "<section class= 'row' style='padding: 1em'>";
                foreach ($artistArray as $item){
                    $name = $item->getName();
                    $nameStripped = strtolower(str_replace(' ', '', $name));

                    echo "<section class='col-2'><img src='img/Artists/{$nameStripped}.png' class='w-100' alt='{$name}'>
                    <a href = '#' class='btn btn-primary' style='border-radius: 0;'>Click here for his performances</a></section>";
                }
                echo "</section>";
            ?>
        </section>

        <section class="container-fluid">

            <section class="row h-100 align-items-center" style="background-color: #C0C0C0">
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a>Friday 27th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a>Saturday 28th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a>Sunday 29th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a>All days</a></section>
            </section>

            <section class="row h-100" style="background-color: #C0C0C0 ">
                <section class="col text-center fonttickets" style="padding: 0.5em; background-color:#FD6A02"><a>Filters ˅</a></section>
            </section>

            <section>
                <?php
                    $activityService = new danceActivityService();

                    (array)$activityArray = $activityService->getActivities();

                    foreach($activityArray as $item){
                        echo $item->getId();
                    }
                ?>
            </section>
        </section>

    </body>

</html>
