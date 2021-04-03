<?php
require_once ("../Service/sessionService.php");
$sesh = new sessionService();

$showPassEntry = (isset($_GET["id"]) && isset($_GET["email"]));
$textGreen = "";
$textRed = "";

if ($showPassEntry){
    $key = $_GET["id"];
    $email = $_GET["email"];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST["emailReq"])){
        try {
            $sesh->createPasswordRecovery($_POST["emailReq"]);
            $textGreen = "Password recovery email sent";
        }
        catch (appException $e) {
            $textRed = $e->getError();
        }
    }
    else if (isset($_POST["email"]) && isset($_POST["id"]) && isset($_POST["password"]) && isset($_POST["passwordAlt"])){
        $key = $_POST["id"];
        $email = $_POST["email"];
        $showPassEntry = true;

        if (strlen($_POST["password"]) > 72)
            $textRed = "Invalid password length";
        else if ($_POST["password"] != $_POST["passwordAlt"])
            $textRed = "Passwords do not match";
        else {
            try {
                $sesh->updatePassword($_POST["email"], (int)$_POST["id"], $_POST["password"]);
                $showPassEntry = false;
                $textGreen = "Updated password";
            }
            catch (appException $e) {
                $textRed = $e->getError();
            }
        }
    }
    else {
        $textRed = "invalid POST";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMS - Password Reset</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if ($showPassEntry) { ?>
        <h2 class="line displayBlock loginWidth center mt-3">Password recovery request</h2>
        <form action="passReset.php" method="post" name="passRec" class="loginWidth center mt-3">
            <input class="displayBlock loginWidth inputBox" type="password" id="password" placeholder="Password" required name="password" maxlength="72">
            <input class="displayBlock loginWidth inputBox" type="password" id="passwordAlt" placeholder="Confirm password" required name="passwordAlt" maxlength="72">
            <input type="hidden" name="id" value="<?php echo $key ?>"/>
            <input type="hidden" name="email" value="<?php echo $email ?>"/>
            <button class="loginWidth floatRight blueButton pAll-1half mt-2 displayInlineBlock">Change Password</button>
        </form>
    <?php } else { ?>
        <h2 class="line displayBlock loginWidth center mt-3">Password recovery</h2>
        <form action="#" method="post" name="passReq" class="loginWidth center mt-3">
            <input class="displayBlock loginWidth inputBox" type="email" id="email" placeholder="Email" required autofocus name="emailReq" maxlength="96">
            <a class="width25 floatLeft greyButton pAll-1half mt-2 displayInlineBlock" href="login.php">Login</a>
            <button class="width66 floatRight blueButton pAll-1half mt-2 displayInlineBlock">Request password recovery</button>
        </form>
    <?php } ?>

    <?php if ($textGreen != "") { ?>
        <p class="center loginWidth"><?php echo $textGreen ?></p>
    <?php } ?>

    <?php if ($textRed != "") { ?>
        <p class="center loginWidth"><?php echo $textRed ?></p>
    <?php } ?>
</body>
