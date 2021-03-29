<?php
require_once "Service/artistService.php";
require_once "Model/danceArtist.php";
require_once "Service/danceActivityService.php";


$activeArray = [];

$activityService = new danceActivityService;

if(isset($_GET['day']) && !empty($_GET['day'])){
    $dayStr = "";

    switch ($_GET["day"]){
        case "saturday":
            $dayStr = "2021-06-28";
            break;
        case "sunday":
            $dayStr = "2021-06-29";
            break;
        default:
            $dayStr = "2021-06-27";
            break;
    }

    $activeArray = $activityService->getAllWithDate($dayStr);
}
else {
    $activeArray = $activityService->getAll();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dance - Haarlem Festival</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dance.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">;
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script></head>

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

    <?php
    $service = new artistService();
    (array)$artistArray = $service->getArtists();

    //all-access
    echo "<section class='row justify-content-center align-items-center' style='background-color: #C0C0C0;
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
                <a href = 'danceartist.php?name={$name}' class='btn btn-primary' style='border-radius: 0;'>Click here for {$name} performances</a></section>";
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
        <section class="row h-100" style="background-color: #C0C0C0 ">
            <section class="col text-center fonttickets" style="padding: 0.5em; background-color: #FD6A02;">
                <a class="btn btn-primary" data-toggle="collapse" href="#filtercollapse">Filters ˅</a>
            </section>
        </section>

        <section class="collapse in" id="filtercollapse">
            <section class="card card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
            </section>
        </section>

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
