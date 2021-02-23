<?php
    require_once "UI/navBar.php"
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home - Haarlem Festival</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>


    <header>
        <div class="title">
            <h1 class="main-title">Welcome to Haarlem Festival</h1>
            <p class="main-title under-title">Haarlem Festival, for the people that enjoy
                <br>a good time
            </p>
        </div>
    </header>

    <section class="container-fluid h-100">
        <h1>Welcome to Haarlem Festival</h1>

        <section class="row justify-content-center align-self-center text-center">
            <section class ="col-10">
                <p>Haarlem is a busy city, with a rich history and beautiful statues and other sights. 
                Walk trough cozy streets in the heart of Haarlem and discover a large variety of stores. Sit down at one of the 
                many pubs with your friends for a drink, or visit one of the many museums or theaters in Haarlem. Haarlem even has a beach 
                you can unwind at. In any case, Haarlem bursts with possibilities for something to do on a weekend out.
                </p>
            </section>
        </section>

        <section class="row justify-content-center align-self-center text-center">
            <section class="col-10">
                <p> To show you Haarlem, we've organised a few events in it! It's called "The Haarlem Festival". If you love <a href="dance.php">dance</a> or 
                <a href="jazz.php">jazz</a> we have a few events planned to help you explore Haarlem. 
                We also have a selection of <a href="food.php">restaurants</a> for you to have dinner at.
                </p>
            </section>
        </section>


        <h1>Discover Haarlem Festival</h1>


        <section class="row justify-content-center align-self-center text-center">
            <section class="col-3">
                <section class="col-12" style="background-color: black; margin-top: 2%;">
                    <h2><a href="dance.php">Dance</a></h2>
                    <img class="eventimg" src="img/Dance.png">
                    <p style="margin-bottom: 0px; padding: 10%" class="discoverBlockContent">Our dance acts focus on dance, house, techno and trance.
                        Haarlem Festival offers 6 of the best DJs in the world. They will perform in back2back and club sessions.
                    </p>
                </section>
                <a href="dance.php" class="btn button1 w-100">Read more</a>
            </section>

            <section class="col-3 h-100">
                <section class="col-12" style="background-color: black; margin-top: 2%;">
                    <h2><a href="jazz.php">Jazz</a></h2>
                    <img class="eventimg" src="img/Jazz.png">
                    <p style="margin-bottom: 0px; padding: 6.5%;" class="discoverBlockContent">
                    Jazz is one of the main acts at the Haarlem Festival.
                    <br>
                    We have artists such as Jonna Frazer, Soul Six and Evolve in house.
                    <br>
                    We also offer a number of free performances.
                    </p>
                </section>
                <a href="jazz.php" class="btn button1 w-100">Read more</a>
            </section>

            <section class="col-3 h-100">
                <section class="col-12" style="background-color: black; margin-top: 2%;">
                    <h2><a href="food.php">Food</a></h2>
                    <img class="eventimg" src="img/Food.png">
                    <p style="margin-bottom: 0px; padding: 6.5%;" class="discoverBlockContent">
                        We have selected several restaurants in the center of Haarlem for you. 
                    These restaurants have delicious dishes for you. Both national and international dishes. 
                    The restaurants have a delicious menu for you at a discounted price.
                    </p>
                </section>
                <a href="food.php" class="btn button1 w-100">Read more</a>
            </section>
        </section>

        <h1 style="margin-top: 2%">This is Haarlem Festival</h1>

        <section class="row justify-content-center align-self-center text-center">
            <section class="col-3">
            <section class="row justify-content-center align-self-center text-center"><img src="img/Icons/thisis_musicnote.png" width="32" height="32"> </section>
                <section class="row" style="padding: 2%;"><p>Together we create an unforgettable musical memory. Our own stories with national and international artists.</p></section>
            </section>

            <section class="col-3">
            <section class="row justify-content-center align-self-center text-center"><img src="img/Icons/thisis_food.png" width="32" height="32"> </section>
                <section class="row" style="padding: 2%;"><p>Enjoy the scents and taste of the national and international dishes of the restaurants nearby.</p></section>
            </section>

            <section class="col-3">
                <section class="row justify-content-center align-self-center text-center"><img src="img/Icons/like.png"> </section>
                <section class="row" style="padding: 2%;"><p>Discover, meet, experience. Find out what the music does to you. Meet and share your experiences.</p></section>
            </section>
        </section>
    </section>
</body>

</html>