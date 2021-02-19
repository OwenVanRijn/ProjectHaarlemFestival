<!DOCTYPE html>
<html>

<head>
    <title>Payment - account</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="keywords" content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>Payment</h1>

    <section>
        <p>Step 1/3</p>
    </section>


    <form method="post" action="payment.php">
        <h2>Your information</h2>
        <h3>Please fill in your personal information</h3>


        <h4>Firstname:</h4>
        <input type="text" placeholder="firstname"></input>

        <h4>Lastname:</h4>
        <input type="text" placeholder="lastname"></input>

        <h4>Emailaddress:</h4>
        <input type="text" placeholder="emailaddress"></input>

        <br><input type="checkbox" id="account" name="account" value="account" onclick="displayAccountFields('moreAccountFields', this)">
        <label for="account"> Make an account</label><br>


        <section id="moreAccountFields" style="display:none">
            <h4>Password:</h4>
            <input type="password" placeholder="password"></input>

            <h4>Phonenumber:</h4>
            <input type="text" placeholder="phonenumber"></input>
        </section>
        <input type="submit" name="submit" value="Submit">


        <script type="text/javascript">
            function displayAccountFields(it, box) {
                var vis = (box.checked) ? "block" : "none";
                document.getElementById(it).style.display = vis;
            }
        </script>
    </form>

    </main>
</body>

</html>