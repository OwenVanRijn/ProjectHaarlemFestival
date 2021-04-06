<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root."/Service/artistOnActivityService.php";
require_once $root."/Service/danceActivityService.php";
require_once $root."/Service/activityService.php";
require_once $root."/Service/danceArtistService.php";
require_once $root."/Service/shoppingcartService.php";
require_once $root."/UI/navBar.php";

$shoppingCartService = new shoppingcartService();
$activityService = new activityService();
$danceService = new danceActivityService();
$artistService = new danceArtistService();
$artist="";
$name = "";

if(!isset($_GET['name'])){
    $name = $_POST['artist'];
}

else{
    $name = $_GET["name"];
}

$artist = $artistService->getFromName($name);
$nameStripped = strtolower(str_replace(' ', '', $name));
$messageString = "";

if(isset($_POST['select'])){
$id = $_POST['select'];

if(is_numeric($id)) {
    $returnedActivity = $activityService->getTypedActivityByIds([$id]);
    if(count($returnedActivity) > 0){
        $id = $returnedActivity[0]->getId();

        $sc = $shoppingCartService->getShoppingcart()->addToShoppingcartItemsById($id, 1);

        $messageString = $sc->getShoppingcartItemsCount();
    }
    else{
        $messageString = "Activity with id $id not found";
    }
}

else{
    $messageString = "Activity id $id is invalid";
}
}

if($name == "" || is_null($artist)){
    header("Location: dance.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $name?> - Haarlem Festival</title>
    <link rel="stylesheet" href="./css/style.css">;
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css";
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <section>
        <section class="row" style="background-color: #666666;margin-top: -2%">
            <section class="col-1">
                <img src='img/Artists/bw/<?php echo $nameStripped?>.png' class="w-80">
            </section>
            <section class="col-11 text-center" style="margin-top: 3em;">
                <h1><?php echo $name?></h1>
            </section>
        </section>
    </section>

    <section class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <a href="dance.php" style="color:#FD6A02"><strong>← Back to dance overview</strong></a>
            <section class="row align-items-center">
                <section class="col text-center">
                    <p class="font-weight-bold"><?php echo $messageString?></p>
                </section>
            </section>

                <?php
                echo "<section class='row text-center' style='margin-top: 2%'>";

                $service = new artistOnActivityService();
                $artistActivity = $service->getActivityByArtist($name);

                if($artistActivity != false){
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
                        echo "<input type='hidden' name='artist' value='$name'>";
                        echo "<button class='button1 w-100' type='submit' name='select' value='{$id}'>Add to cart</a>";
                        echo "</section>";
                    }
                }
                ?>
        </form>

        <section class='row justify-content-center align-self-center text-center' style='margin: 2%'>
                <section class= 'row justify-content-center align-self-center text-center'>
                    <h2>Get to know the artist:</h2>
                </section>

                <section class='row justify-content-center align-self-center text-center'>
                    <section class='col-8'>
                    <?php
                        echo $artist->getDescription();
                    ?>
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
<script>
    function addTicket(str){

        <?php
        ?>
    }
</script>
</html>