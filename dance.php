<?php
require_once "Service/danceArtistService.php";
require_once "Service/artistOnActivityService.php";
require_once "Model/danceArtist.php";
require_once "Service/danceActivityService.php";
require_once "Service/activityService.php";
require_once "Service/shoppingcartService.php";


//SERVICES
$danceService = new danceActivityService();
$activityService = new activityService();
$artistService = new danceArtistService();
$artistOnActivityService = new artistOnActivityService();
$shoppingCartService = new shoppingcartService();

$activeArray = $danceService->getAll(); //active selection activities
$dateArray = []; //all unique dates
$typeArray = []; //all unique session types
$idArray = []; //all activitiyids

//ALL-ACCESS DATES AND STRING DATA
$daysAllAccess = ["2021-06-27","2021-06-28","2021-06-29"];
$accessPrice = "";
$accessDate = "";
$accessId = "";

//MESSAGE LINE
$messageString = "";

$danceArray = $activityService->getByType("dance"); //all Danceactivities as regular activity

//LOAD DATES FROM DB
foreach ($danceArray as $activity){
    $date = $activity->getDate(); //Get all dates from activity with type dance

    if(!in_array($date, $dateArray)){ //checks duplicate date
        $dateArray[] = $date;
    }
}


//GET SESSIONTYPE FROM DB
foreach ($danceArray as $activity){
    $id = $activity->getId();
    $idArray[] = $id;
}

$returnedDanceActivities = $activityService->getTypedActivityByIds($idArray); //Returns type Danceactivity

foreach ($returnedDanceActivities as $activity){
    $type = $activity->getType(); //returns session type

    if(!in_array($type, $typeArray)){ //puts unique sessiontype values in array
        $typeArray[] = $type;
    }
}

//DATE FILTER
if(isset($_GET['day']) && !empty($_GET['day'])) {
    $datePicked = $_GET['day']; //date that got selected in view
    $dateFormatArray = array();

    foreach($dateArray as $day){
        $dayFormatted = $day->format("Y-m-d"); //Format to correct date value
        $dateFormatArray[] = $dayFormatted;
    }


    if(in_array($datePicked, $dateFormatArray)){ //if GET value is in the pre-determined date array
        $returnArray = $danceService->getAllWithDate($datePicked);

        if($returnArray != null){
            $activeArray = $returnArray;
        }

        $accessDateAll = $datePicked; //for All-Access tickets
        if($datePicked == "2021-06-27"){
            $accessId = 135;
            $accessPrice = 125;
        }

        else if($datePicked == "2021-06-28"){
            $accessId = 136;
            $accessPrice = 150;
        }

        else{
            $accessId = 137;
            $accessPrice = 150;
        }
    }

    else{ //if input not in array, show all activities
        $activeArray = $danceService->getAll();
    }
}

//SESSION & DATE
if (isset($_POST['artist']) && isset($_POST['type'])){ //if both artist and session got POST action
    $artist = $_POST['artist'];
    $type = $_POST['type'];

    $activities = $artistOnActivityService->getBySessionAndArtist($artist, $type);

    $activeArray = $activities;
}

//SESSION FILTER
else if(isset($_POST['type'])) { //if only session gets POST value

    $type = $_POST['type'];
    $activities = $danceService->getActivityBySessionType($type);

    $activeArray = $activities;
}

//ARTIST FILTER
else if(isset($_POST['artist'])) { //if only artist gets POST value;
    $artist = $_POST['artist'];

    $aoaArray = $artistOnActivityService->getActivityByArtist($artist);

    $activeArray = $aoaArray;
}

//NULL CHECK FOR MESSAGE
if(count($activeArray) == 0){
    $messageString = "No match found";
}

//SHOPPING CART
if(isset($_POST['selectedId'])) {
    $id = $_POST['selectedId'];

    if (is_numeric($id)) {

        $danceActivity = $danceService->getActivityFromId($id);

        if ($danceActivity == null) {
            $messageString = "Can't find activity $id";
        } else {
            $shoppingCartService->getShoppingcart()->addToShoppingcartItemsById($danceActivity->getActivity()->getActivity()->getId(), 1);
            $messageString = "Your ticket has been added to the shoppingcart!";
        }
    } else {
        $messageString = "Activity id $id is invalid";
    }
}

//SHOPPING CART FOR ALL-ACCESS TICKETS
if(isset($_POST['all-access'])){

    $id = $_POST['all-access'];
    $activity = $activityService->getById($id);

    if(is_numeric($id)){
        if($activity != null){
            $shoppingCartService->getShoppingcart()->addToShoppingcartItemsById($activity->getId(), 1);
        }

        else{
            $messageString = "can't find activity $id";
        }
    }
    else{
        $messageString = "Activity id $id is invalid";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dance - Haarlem Festival</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dance.css">
    <link rel="stylesheet" href="css/style.css">
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
    <!--All access-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <section class='row h-100 align-items-center' style='background-color: #9d9d9d; margin: 2% 25% 2% 25%;padding: 1% 0 1% 0;'>
            <section class='col-8 fonttickets'>All in ticket (Friday/Saturday/Sunday): €250,-</section>
            <section class='col-4 text-right'><button class='button1' type="submit" name="all-access" value="134">Add Ticket</button></section>
        </section>
    </form>

    <!--Artist Row-->
    <?php
    (array)$artistArray = $artistService->getAll();
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
    <!--Dates-->
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

    <!--Filters-->
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
                            $artistArray = $artistService->getAll();
                            foreach ($artistArray as $artist){
                                $name = $artist->getName();
                                echo "<option value='$name'>$name</option>";
                            }
                            ?>
                        </select>
                    </section>
                    <section class="col-3">
                        <label for="type" class="font-weight-bold">Session Type</label>
                        <select name="type">
                            <option disabled selected>Type</option>
                            <?php
                            foreach ($typeArray as $type){
                                echo "<option value='$type'>$type</option>";
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

    <!--Activities-->
    <section class="container-fluid">
        <?php
        if(isset($_GET['day'])){
            if(in_array($accessDate, $daysAllAccess)){
                echo "<form action='#' method='post'>";
                echo "<section class='row h-100 align-items-center' style='background-color: #9d9d9d; margin: 2% 25% 2% 25%;padding: 1% 0 1% 0;'>";
                echo "<section class='col-8 fonttickets'>All-access ticket ($accessDate): $accessPrice,-</section>";
                echo "<section class='col-4 text-right'><button class='button1' type='submit' name='all-access' value='{$accessId}'>Add Ticket</button></section>";
                echo "</section>";
                echo "</form>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <?php
            if($messageString != ""){
                echo "<section class='row' style='margin-top: 1%'>";
                echo "<section class='col text-center'>";
                echo "<p class='font-weight-bold'>$messageString</p>";
                echo "</section>";
                echo "</section>";
            }

            echo "<section class='row' style='margin-top: 1%'>";
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
                echo "<button class='button1 w-100' name='selectedId' value='$id'>Add to cart</button>";
                echo "</section>";

            }
            echo "</section>";
            ?>
        </form>
    </section>
</section>

</body>

</html>