<?php
require_once "UI/navBar.php";
require_once "Service/jazzActivityService.php";
require_once "Service/jazzbandService.php"
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css";
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">

    <title>Jazz</title>
</head>
<body>
<header class="title">

    <h1 class="main-title">Jazz</h1>
    <p class="main-title under-title">Haarlem Festival, for the people that enjoy a good time</p>

</header>

<main class="content">
    <h1>Our Haarlem Jazz performances.</h1>
    <section>

        <section id="filter">
            <section class="filterdays">
                <label for="days">Day</label>
                <select name="days">
                    <option value="1">Thursday 26th July</option>
                    <option value="2">Friday 27th July</option>
                    <option value="3">Saturday 28th July</option>
                    <option value="4">Sunday 29th July</option>
                </select>

            </section>

            <section class="filterlocation">
                <label for="locations">Location</label>
                <select name="locations">
                    <option value="1">Main Hall</option>
                    <option value="2">Second Hall</option>
                    <option value="3">Third Hall</option>
                    <option value="4">Grote Markt</option>
                </select>

            </section>

            <section class="searchbar">
                <label for="SearchBar">Search</label>
                <input type="text" name="SearchBar" placeholder="Search..">
                <button type="submit"><i class="fa fa-search"></i></button>
            </section>

        </section>
    </section>
    <section>
        <?php
        $service = new jazzActivityService();
        (array)$jazzActivities = $service->getAll();
        echo "<section class='row' style='margin-top: 2%'>";

        foreach ($jazzActivities as $activities) {
            echo "<section class='col-4 box'>";

            $name = $activities->getJazzband()->getName();

            $starttime = $activities->getActivity()->getStartTime()->format("H:i");
            $endtime = $activities->getActivity()->getEndTime()->format("H:i");
            echo "<section class='col-12 text-center' style='background-color: grey; color: black; padding-top: 2%;'>";
            echo "<p style='color: orange; font-weight: bold'>{$name}</p>";
            echo "<section class='row justify-content-center align-self-center text-center'><p style='color: orange; font-weight: bold'></p>{$starttime} - {$endtime}</section>";
            echo "<input type='submit' class='btn btn-primary'style='background-color: orange; color: white; name='moreinformation' value='More information'></input>";
            echo "<input type='submit' class='btn btn-primary'style='background-color: orange; color: white; name='addtocart' value='Add to cart'></input>";
            echo "</section>";
            echo "</section>";
        }
        echo "</section>";
        ?>
    </section>
</main>
<?php
require_once "UI/footer.php";
?>
</body>
</html>
