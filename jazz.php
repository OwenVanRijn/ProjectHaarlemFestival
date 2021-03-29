<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "Service/jazzActivityService.php");
require_once($root . "Service/jazzbandService.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="keywords"
          content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">

    <title>Jazz</title>
</head>
<body>
<?php
require_once($root . "UI/navBar.php");
?>
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
                <select class="filterJazzDay" name="days">
                    <option value="all">-</option>
                    <option value="Thursday 26th July">Thursday 26th July</option>
                    <option value="Friday 27th July">Friday 27th July</option>
                    <option value="Saturday 28th July">Saturday 28th July</option>
                    <option value="Sunday 29th July">Sunday 29th July</option>
                </select>

            </section>

            <section class="filterlocation">
                <label for="locations">Location</label>
                <select name="locations">
                    <option value="all">-</option>
                    <option value="Main Hall">Main Hall</option>
                    <option value="Second Hall">Second Hall</option>
                    <option value="Third Hall">Third Hall</option>
                    <option value="Grote Markt">Grote Markt</option>
                </select>

            </section>

            <section class="searchbar">
                <label for="SearchBar">Search</label>
                <input id="myInput" onkeyup="searchJazzBand()" type="text" name="SearchBar" placeholder="Search..">
            </section>

        </section>
    </section>
    <section>
        <?php
        $service = new jazzactivityService();
        (array)$jazzActivities = $service->getAll();
        echo "<ul id='myUL' class='activityBoxes'>";

        foreach ($jazzActivities as $activities) {

            $name = $activities->getJazzband()->getName();
            $description = $activities->getJazzband()->getDescription();
            $location = $activities->getHall();
            $price = "€" . $activities->getActivity()->getPrice();

            $starttime = $activities->getActivity()->getStartTime()->format("H:i");
            $endtime = $activities->getActivity()->getEndTime()->format("H:i");
            $id = $activities->getActivity()->getId();
            echo "<li class='jazzBand' data-id='$id' title='$name'";
            echo "<p style='color: black; text-align: center; font-size: 150%; font-weight: 900'>{$name} &emsp; {$price}</p>";
            echo "<img src='img/Bands/jazz$id.png' width='250' height='250'>";
            echo "<p style='color: black; text-align: center; font-weight: bold'>{$starttime} - {$endtime}</p>";
            echo "<p style='color: black; text-align: center; font-weight: bold'>{$location}</p>";
            echo "<input type='button' id='btnMoreInfo'style='background-color: orange; width: 50%; color: white; name='moreinformation' value='More information'></input>";
            echo "<input type='submit' id='btn btn-primary'style='background-color: orange; width: 50%; color: white; name='addtocart' value='Add to cart'></input>";
            echo "</li>";

            echo "<div id='myModal' class='modal'>";
            echo "<div class='modal-content'>";
            echo "<span class='close'>&times;</span>";
            echo "<p>$description</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</ul>";
        ?>
    </section>
    <script>
        function searchJazzBand() {

            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                txtValue = li[i].title;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
</main>


<script type="text/javascript">
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("btnMoreInfo");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
<?php
require_once "UI/footer.php";
?>
</body>
</html>
