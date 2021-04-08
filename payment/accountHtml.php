<!DOCTYPE html>
<html>

<head>
    <title>Payment - account</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/shoppingcart.css">
    <link rel="stylesheet" href="../css/navBarFooter.css">
    <meta charset="UTF-8">
    <meta name="keywords"
          content="Haarlem, festival, jazz, food, history, party, feest, geschiedenis, eten, restaurant">
    <meta name="description" content="Haarlem Festival">
    <meta name="author" content="Haarlem Festival">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<section class="contentShoppingcart content">
    <section class="contentAccount">
        <h1>Payment</h1>

        <section>
            <p class="step">Step 1 / 3</p>
        </section>


        <form method="post">
            <h2>Your information</h2>
            <h4>Please fill in your personal information</h4>


            <h4 class="labelInputField">Firstname</h4>
            <input type="text" placeholder="firstname" name="firstname" maxlength="40" size="20" required>

            <h4 class="labelInputField">Lastname</h4>
            <input type="text" placeholder="lastname" maxlength="40" name="lastname" size="20" required>

            <h4 class="labelInputField">Emailaddress</h4>
            <input type="text" placeholder="emailaddress" maxlength="40" name="email" size="25" required>

            <section>
                <input class="stepNext" type="submit" name="submit" value="Next step">
            </section>
        </form>
    </section>
</section>
</body>

</html>