<?php
    require_once "Service/artistService.php";
    require_once "Model/danceArtist.php";
    require_once "Service/danceActivityService.php";
    require_once "UI/navBar.php";


    $activeArray = [];

    $activityService = new danceActivityService;
    (array)$activityArray = $activityService->getAll();

    if(!isset($_GET['day'])){
        foreach($activityArray as $item){
            array_push($activeArray, $item);
        }
    }

    if(isset($_GET['day']) && !empty($_GET['day'])){ 

        foreach($activityArray as $item){
            
            if($_GET['day'] == 'friday'){
                if($item->getActivity()->getDate()->format("d-m") == "27-06"){
                    array_push($activeArray, $item);
                }
            }

            else if($_GET['day'] == 'saturday'){
                if($item->getActivity()->getDate()->format("d-m") == "28-06"){
                    array_push($activeArray, $item);
                }
            }

            else if($_GET['day'] == 'sunday'){
                if($item->getActivity()->getDate()->format("d-m") == "29-06"){
                    array_push($activeArray, $item);
                }
            }
        }
    }
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

                echo "<section class='col-2'>
                <img src='img/Artists/{$nameStripped}.png' class='w-100' alt='{$name}'>
                <a href = '#' class='btn btn-primary' style='border-radius: 0;'>Click here for {$name} performances</a></section>";
            }
            echo "</section>";
        ?>
    </section>

    <section class="container-fluid">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
            <section class="row h-100 align-items-center" style="background-color: #C0C0C0">
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a href="dance.php?day=friday" style="color: black;">Friday 27th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a href="dance.php?day=saturday" style="color: black;">Saturday 28th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a href="dance.php?day=sunday" style="color: black;">Sunday 29th of July</a></section>
                <section class="col-3 text-center fonttickets" style="border-width:0.2em; border-style: solid; padding: 0.5em;"><a href="dance.php" style="color: black;">All days</a></section>
            </section>
        </form>

        <section class="row h-100" style="background-color: #C0C0C0 ">
            <section class="col text-center fonttickets" style="padding: 0.5em; background-color: #FD6A02;"><a>Filters ˅</a></section>
        </section>

        <section class="container-fluid">
            <?php
                echo "<section class='row' style='margin-top: 2%'>";

                foreach($activeArray as $item){

                    echo "<section class='col-4 box'>";
                    echo "<section class='col-12 text-center' style='background-color: black; color: white; padding-top: 2%;'>";
                    $artiststrarray = $item->getArtists();
                    $artists = "";

                    foreach ($artiststrarray as $artist) {
                        $artists .= $artist->getName() . " "; 
                    }

                    $time = $item->getActivity()->getStartTime()->format("H:i");

                    $location = $item->getActivity()->getLocation()->getName();

                    $session = $item->getType();

                    $price = "€".$item->getActivity()->getPrice().",-";

                    echo "<p style='color: orange; font-weight: bold'>{$artists}</p>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Start time:</p>{$time}</section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Location:</p><bold>{$location}</bold></section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Session:</p><bold>{$session}</bold></section>";
                    echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'>Price:</p><bold>{$price}</bold></section>";
                    echo "</section>";
                    echo "<a href='#' class='btn btn-primary w-100'>Add to cart</a>";
                    echo "</section>";
                }
                echo "</section>";
            ?>
        </section>
    </section>

</body>

</html>
