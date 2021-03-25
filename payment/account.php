<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/UI/navBar.php");
require_once ("./Service/customerService.php");


if(isset($_POST['submit'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $customer = new customerService();

    $customer->addCustomer($firstname, $lastname, $email);

    $id = $customer->getFromEmail($email)->getId();

    //header("location: https://haarlemfestival.louellacreemers.nl/payment/payment.php?id={$id}");
}

?>

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
            <input type="text" placeholder="firstname" name="firstname" maxlength="40" size="20"></input>

            <h4 class="labelInputField">Lastname</h4>
            <input type="text" placeholder="lastname" maxlength="40" name="lastname" size="20"></input>

            <h4 class="labelInputField">Emailaddress</h4>
            <input type="text" placeholder="emailaddress" maxlength="40" name="email" size="25"></input>


            <br><input type="checkbox" id="account" name="account" value="account"
                       onclick="displayAccountFields('moreAccountFields', this)">
            <label for="account"> Make an account</label><br>


            <section id="moreAccountFields" style="display:none">
                <h4 class="labelInputField">Password</h4>
                <input type="password" placeholder="password"></input>

                <h4 class="labelInputField">Phonenumber</h4>
                <input type="text" placeholder="phonenumber" maxlength="10" size="10"></input>
            </section>

            <script type="text/javascript">
                function displayAccountFields(it, box) {
                    var visable = (box.checked) ? "block" : "none";
                    document.getElementById(it).style.display = visable;
                }
            </script>
            <section>
                <input class="stepNext" type="submit" name="submit" value="Next step">
            </section>
        </form>
    </section>
</section>
</body>

</html>