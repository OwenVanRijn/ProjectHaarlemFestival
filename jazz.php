<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/Service/jazzactivityService.php";
require_once $root . "/Service/jazzBandService.php";
require_once $root . "/Service/shoppingcartService.php";

$service = new jazzactivityService();
$shoppingCartService = new shoppingcartService();

if(isset($_POST['selectedAct'])){
    $id = $_POST['selectedAct'];

    $jazzActivity = $jazzService->getActivityFromId($id);

    if(is_array($jazzActivity)){
        $jazzActivity = $jazzActivity[0];
    }

    $shoppingCartService->getShoppingcart()->setShoppingcartItemById($jazzActivity->getActivity()->getId(), 1);
}
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
    <meta charset="UTF-8">

    <title>Jazz</title>
</head>
<body>
<?php
require_once("UI/navBar.php");
?>
<header class="title">

    <h1 class="main-title">Jazz</h1>
    <p class="main-title under-title">Haarlem Festival, for the people that enjoy a good time</p>

</header>

<main class="content">
    <h1>Our Haarlem Jazz performances.</h1>
    <p id="demo"></p>
    <section>
      <?php
      (array)$jazzActivities = $service->getAll();
      $dates = array();
      $locations = array();
      foreach ($jazzActivities as $a) {
        $dateOfAct = $a->getActivity()->getDate()->format("l jS F");
        $locationAct = $a->getHall();
        if(!in_array($dateOfAct,$dates)){
          array_push($dates,$dateOfAct);
        }
        if(!in_array($locationAct,$locations)){
          array_push($locations,$locationAct);
        }
      }
      ?>
        <section id="filter">
            <section class="filterdays">
                <label for="days">Day</label>
                <select id="filterJazzDay" class="filterJazzDay" onchange="filterDay()" name="days">
                  <option value="all">-</option>
                  <?php
                  foreach ($dates as $dayFilter){
                    echo "<option value='$dayFilter'>{$dayFilter}</option>";
                  }
                   ?>
                </select>

            </section>

            <section class="filterlocation">
                <label for="locations">Location</label>
                <select name="locations">
                    <option value="all">-</option>
                    <?php
                    foreach ($locations as $locationFilter){
                      echo "<option value='$locationFilter'>{$locationFilter}</option>";
                    }
                     ?>
                </select>

            </section>

            <section class="searchbar">
                <label for="SearchBar">Search</label>
                <input id="myInput" onkeyup="searchJazzBand()" type="text" name="SearchBar" placeholder="Search..">
            </section>

        </section>
    </section>
    <section>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <?php
        echo "<ul id='myUL' class='activityBoxes'>";
        foreach ($jazzActivities as $activities) {

            $name = $activities->getJazzband()->getName();
            $description = $activities->getJazzband()->getDescription();
            $location = $activities->getHall();
            $price = "€" . $activities->getActivity()->getPrice();
            $date = $activities->getActivity()->getDate()->format("l jS F");

            $starttime = $activities->getActivity()->getStartTime()->format("H:i");
            $endtime = $activities->getActivity()->getEndTime()->format("H:i");
            $id = $activities->getJazzband()->getId();
            $activityId = $activities->getActivity()->getId();
            echo "<li class='jazzBand' data-id='$id' title='$name'";
            echo "<p style='color: black; text-align: center; font-size: 150%; font-weight: 900'>{$name} &emsp; {$price}</p>";
            echo "<img src='img/Bands/jazz$id.png' width='250' height='250'>";
            echo "<p class='date' title='$date' style='color: black; text-align: center; font-weight: bold'>{$date}  {$starttime} - {$endtime}</p>";
            if($location != 'unknown')
            {
            echo "<p style='color: black; text-align: center; font-weight: bold'>{$location}</p>";
            echo "<input type='button' class='btnMoreInfo' href='#myModal$id' style='background-color: orange; width: 50%; color: white; name='moreinformation' value='More information'></input>";
            echo "<input type='submit' style='background-color: orange; width: 50%; color: white; name='selectedAct' value='$activityId'></input>";
            }
            else {
              echo "<p style='color: black; text-align: center; font-weight: bold'>Grote Markt</p>";
              echo "<input type='button' id='btnMoreInfo'style='background-color: orange; width: 100%; color: white; name='moreinformation' value='More information'></input>";
            }
            echo "</li>";

            echo "<div id='myModal$id' class='modal'>";
            echo "<div class='modal-content'>";
            echo "<span class='close'>&times;</span>";
            echo "<p style='color: black; text-align: center; font-weight: bold'>$name</p>";
            echo "<p>$description</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</ul>";
        ?>
      </form>
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
    <script type="text/javascript">
     function filterDay(){
       // Variables
     var dropdown, ul, li, txtValue, filter;
     dropdown = document.getElementById("filterJazzDay");
     ul = document.getElementById("myUL");
     li = ul.getElementsByTagName("li");
     filter = dropdown.value;

     for (i = 0; i < li.length; i++) {
       p = li[i].getElementsByClassName('date');
       txtValue = p.title;
       document.getElementById("demo").innerHTML = txtValue;
       if (txtValue === filter || filter === "all") {
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
var modals = document.getElementsByClassName('modal');

// Get the button that opens the modal
var btn = document.getElementsByClassName("btnMoreInfo");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close");


for (var i = 0; i < btn.length; i++) {
  btn[i].onclick = function(e) {
    e.preventDefault();
    modal = document.querySelector(e.target.getAttribute("href"))
    modal.style.display = "block";
  }
}

// When the user clicks on <span> (x), close the modal
span[0].onclick = function() {
    modal[0].style.display = "none";
}

span[1].onclick = function() {
    modal[1].style.display = "none";
}
window.onclick = function(event) {
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
