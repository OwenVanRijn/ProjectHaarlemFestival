<?php
require_once "Service/artistService.php";
require_once "Service/artistOnActivityService.php";
require_once "Model/danceArtist.php";
require_once "Service/danceActivityService.php";
require_once "Service/activityService.php";
require_once "Service/shoppingcartService.php";


$activeArray = [];
$dateArray = [];
$locationArray = [];

$danceService = new danceActivityService();
$activityService = new activityService();
$artistService = new artistService();
$artistOnActivityService = new artistOnActivityService();
$shoppingCartService = new shoppingcartService();

$danceArray = $activityService->getByType("dance");


//LOAD DATES FROM DB
foreach ($danceArray as $activity){
    $date = $activity->getDate();

    if(!in_array($date, $dateArray)){
        $dateArray[] = $date;
    }
}

//GET LOCATIONS FROM DB
foreach ($danceArray as $activity){
    $location = $activity->getLocation()->getName();

    if(!in_array($location, $locationArray)){
        $locationArray[] = $location;
    }
}


//DATE FILTER
if(isset($_GET['day']) && !empty($_GET['day'])) {

    $datePicked = $_GET['day'];
    $activeArray = $danceService->getAllWithDate($datePicked);
}

else {
    $activeArray = $danceService->getAll();
}

//LOCATION FILTER
if(isset($_POST['location'])){
    $location = $_POST['location'];

    $dateArray = $activityService->getByLocation($location);

    foreach ($dateArray as $activity){
        $id = $activity->getId();

        $ids[] = $id;
    }

    $array = $activityService->getTypedActivityByIds($ids);

    foreach ($array as $ja){
        var_dump();
        echo "<br>";
    }

    $activeArray = $array;
}

//ARTIST FILTER
if(isset($_POST['artist'])) {
    $artist = $_POST['artist'];

    $aoaArray = $artistOnActivityService->getActivityByArtist($artist);
    $activityArray = [];

    foreach ($aoaArray as $item) {
        $activity = $item->getActivity()->getArtists();
    }

    $activeArray = $activityArray();
}

if(isset($_POST['selectedId'])){
    $id = $_POST['selectedId'];

    $danceActivity = $danceService->getActivityFromId($id);

    if(is_array($danceActivity)){
        $danceActivity = $danceActivity[0];
    }

    $shoppingCartService->getShoppingcart()->setShoppingcartItemById($danceActivity->getActivity()->getActivity()->getId(), 1);
}

if(isset($_POST['all-access'])){

    $id = $_POST['all-access'];
    $activity = $activityService->getById($id);

    $shoppingCartService->getShoppingcart()->setShoppingcartItemById($activity->getId(), 1);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dance - Haarlem Festival</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dance.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>

<body>

<?php
require_once ("UI/navBar.php");
?>

<header>
    <div class="title">
        <h1 class="main-title">Dance</h1>
        <p class="main-title under-title">Haarlem Festival, for the people that enjoy
            <br>a good time
        </p>
    </div>
</header>

<section class="container h-100">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <section class='row h-100 align-items-center' style='background-color: #C0C0C0; margin: 2% 25% 2% 25%;padding: 1% 0 1% 0;'>
            <section class='col-8 fonttickets'>All in ticket (Friday/Saturday/Sunday): €250,-</section>
            <section class='col-4 text-right'><button class='btn btn-primary' type="submit" name="all-access" value="134">Add Ticket</button></section>
        </section>
    </form>

    <?php
    (array)$artistArray = $artistService->getArtists();
    //Lineup
    echo "<section class= 'row' style='padding: 1em'>";
    foreach ($artistArray as $item){
        $name = $item->getName();
        $nameStripped = strtolower(str_replace(' ', '', $name));
        echo "<section class='col-2'>
                <img src='img/Artists/{$nameStripped}.png' class='w-100' alt='{$name}'>
                <a href = 'danceartist.php?name={$name}' class='btn btn-primary' style='border-radius: 0;'>Click here for {$name} performances</a></section>";
    }
    echo "</section>";
    ?>
</section>

<section class="container-fluid">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
        <?php
            echo "<section class='row h-100 align-items-center' style='background-color: #C0C0C0'>";

            foreach ($dateArray as $date){
                $dateFormat = $date->format("Y-m-d");
                $datePrint = $date->format("F j");

                echo "<section class='col text-center fonttickets' style='border-width:0.2em; border-style: solid; padding: 0.5em;'><a href='dance.php?day=$dateFormat' style='color: black;'>{$datePrint}th</a></section>";
            }
            echo "<section class='col text-center fonttickets' style='border-width:0.2em; border-style: solid; padding: 0.5em;'><a href='dance.php' style='color: black;'>All days</a></section>";
            echo "</section>";
        ?>
    </form>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <section class="row h-100 align-items-center" style="background-color: #C0C0C0 ">
            <section class="col text-center fonttickets" style="padding: 0.5em; background-color: #FD6A02;">
                <a class="btn btn-primary" data-toggle="collapse" href="#filtercollapse">Filters ˅</a>
            </section>
        </section>

        <section class="collapse in" id="filtercollapse">
            <section class="card card-body">
                <section class="row">
                    <section class="col-3">
                        <label for="artist" class='font-weight-bold'>Artist</label>
                        <select name="artist">
                            <option disabled selected>Artists</option>
                            <?php
                            $artistArray = $artistService->getArtists();
                            foreach ($artistArray as $artist){
                                $name = $artist->getName();;
                                echo "<option value='$name'>$name</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section class="col-3">
                        <label for="location" class="font-weight-bold">Location</label>
                        <select name="location">
                            <option disabled selected>Location</option>
                            <?php
                            foreach ($locationArray as $location){
                                echo "<option value='$location'>$location</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section class="col-6 text-right">
                        <button class="btn btn-primary">></button>
                    </section>
                </section>
            </section>
        </section>
    </form>

    <section class="container-fluid">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
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

                $id = $item->getId();

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
                echo "<button class='btn btn-primary w-100' name='selectedId' value='$id'>Add to cart</button>";
                echo "</section>";
            }
            echo "</section>";
            ?>
        </form>
    </section>
</section>

</body>

</html>
