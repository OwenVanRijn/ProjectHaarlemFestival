<?php
require_once("../Service/sessionService.php");
$sessionService = new sessionService();

$username = "";
$password = "";

# Probably a login request
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST["username"]))
        $username = $_POST["username"];

    if (isset($_POST["password"]))
        $password = $_POST["password"];

    try {
        $sessionService->createSession($username, $password);
    } catch (appException $e) {
        $err = $e->getError();
    }
}

$user = $sessionService->validateSessionFromCookie();

if ($user)
    header("Location: home.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMS - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2 class="line displayBlock loginWidth center mt-3">Login:</h2>
<form action="#" method="post" class="loginWidth center mt-3">
    <input class="displayBlock loginWidth inputBox" type="text" id="username" placeholder="Username" required autofocus name="username" value="<?php echo $username ?>" maxlength="96">
    <input class="displayBlock loginWidth inputBox" type="password" id="password" placeholder="Password" required name="password" maxlength="72">
    <a class="almostHalfWidth floatLeft greyButton pAll-1half mt-2 displayInlineBlock" href="passReset.php">Recover password</a>
    <button class="almostHalfWidth floatRight blueButton pAll-1half mt-2 displayInlineBlock">Sign in</button>
    <?php
    if (isset($err)){
        echo "<p class='mt-2 text-center text-danger txt-sm'>Failed to sign in.<br/>$err</p>";
    }
    ?>
</form>
</body>
</html>

