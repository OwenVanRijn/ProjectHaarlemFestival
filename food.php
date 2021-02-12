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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
                        <label class="label" for="3stars">3 stars<br></label>
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
                    <p class="filterlabelSubtitle"><br>Search for a restaurant</p>
                    <form action="/action_page.php">
                        <input type="text" placeholder="Search.." name="search">
                        <button type="submit" class="button1">Search</button>
                    </form>
                </section>
            </section>
        </section>



        <div class="w3-container">
            <h2>W3.CSS Login Modal</h2>
            <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-green w3-large">Reserveer</button>

            <div id="id01" class="w3-modal">
                <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
                    <form class="w3-container" action="/action_page.php">

                        <h1>Reservation Restaurant Fris</h1>

                        <section class="reservationsection">
                            <label><b>Amount of seats</b></label>

                            <select name="seats" id="seats">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </section>

                        <section class="reservationsection">
                            <label class="labelTitle">Date<br></label>
                            <input type="radio" class="date" name="date" id="date1" value="1">
                            <label for="date1">17:30 - 19:00</label><br>
                            <input type="radio" id="date" name="date" id="date2" value="2">
                            <label for="date2">19:00 - 20:30</label><br>
                            <input type="radio" id="date" name="date" id="session3" value="3">
                            <label for="date3">20:30 - 22:00</label><br><br>
                        </section>

                        <br>
                        <section class="reservationsection">
                            <label class="labelTitle">Session<br></label>
                            <input type="radio" class="session" name="session" id="session1" value="1">
                            <label for="session1">17:30 - 19:00</label><br>
                            <input type="radio" id="session" name="session" id="session2" value="2">
                            <label for="session2">19:00 - 20:30</label><br>
                            <input type="radio" id="session" name="session" id="session3" value="3">
                            <label for="session3">20:30 - 22:00</label><br><br>
                        </section>

                        <section class="reservationsection">
                            <label class="labelTitle">Note</label>
                            <p>Do you have any dietary requirements, allergies or other comments?</p>
                            <textarea id="noteTextArea" rows="3"></textarea>
                        </section>


                    <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancel</button>
                        <input class="w3-button w3-green w3-right w3-padding" type="submit" name="reservation" id="session3" value="Send">
                    </div>
                    </form>

                </div>
            </div>
        </div>

    </main>
</body>

</html>