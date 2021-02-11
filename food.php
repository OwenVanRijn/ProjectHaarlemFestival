<!DOCTYPE html>
<html>

<head>
    <title>Food</title>
    <link rel="stylesheet" href="Style/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>


    <header>
        <div class="title">
            <h1 class="main-title">Food</h1>
            <p class="main-title under-title">Haarlem has several restaurants in the center.
                <br>
                We have selected some of these restaurants for you and give you a great experience.
            </p>
        </div>
    </header>

    <main class="content">

        <p>We have selected a few restaurants for you. At these restaurants you can enjoy delicious food at a competitive price.
            You pay € 10.00 per person in advance in reservation costs.
            In addition, children under the age of twelve receive a 50% discount on their dinner.
        </p>
        <h1>Our restaurants</h1>


        <section>
            <section id="filterbar">
                <section class="stars">
                    <p class="filterlabelSubtitle">Stars</p>
                    <section class="checkboxesStars">
                        <input type="checkbox" class="filterCheckbox" id="3stars" name="3stars" checked>
                        <label class="label" for="3stars">3 stars</label>
                        <input type="checkbox" class="filterCheckbox" id="4stars" name="4stars" checked>
                        <label class="label" for="4stars">4 stars</label>
                    </section>
                </section>

                <section class="cuisine">
                    <p class="filterlabelSubtitle">Cuisine</p>

                    <select name="cuisine" id="cuisine">
                        <option value="1">Argentinian</option>
                        <option value="2">Dutch</option>
                        <option value="3">European</option>
                        <option value="4">Fish</option>
                        <option value="5">French</option>
                    </select>
                </section>

                <section class="searchbar">
                    <p class="filterlabelSubtitle">Search for a restaurant</p>
                    <form action="/action_page.php">
                        <input type="text" placeholder="Search.." name="search">
                        <button type="submit" class="button1">Search</button>
                    </form>
                </section>
            </section>
        </section>

        <section class="row">
            <section class="discoverBlockColumn">
                <h2><a href="dance.php">Dance</a></h2>
                <img class="eventimg" src="Pictures/Dance.png">
                <p class="discoverBlockContent">Our dance acts focus on dance, house, techno and trance.
                    <br>
                    Haarlem Festival offers 6 of the best DJs in the world. They will perform in back2back and club sessions.
                </p>
                <a href="dance.php" class="button1">Read more</a>
            </section>

            <section class="discoverBlockColumn">
                <h2><a href="jazz.php">Jazz</a></h2>
                <img class="eventimg" src="Pictures/Jazz.png">
                <p class="discoverBlockContent">Read moreJazzJazz is one of the main acts at the Haarlem Festival.
                    <br>
                    We have artists such as Jonna Frazer, Soul Six and Evolve in house.
                    <br>
                    We also offer a number of free performances.
                </p>
                <a href="jazz.php" class="button1">Read more</a>
            </section>

            <section class="discoverBlockColumn">
                <h2><a href="food.php">Food</a></h2>
                <img class="eventimg" src="Pictures/Food.png">
                <p class="discoverBlockContent">We have selected several restaurants in the center of Haarlem for you. These restaurants have delicious dishes for you. Both national and international dishes. The restaurants have a delicious menu for you at a discounted price.
                </p>
                <a href="food.php" class="button1">Read more</a>
            </section>
        </section>
    </main>
</body>

</html>