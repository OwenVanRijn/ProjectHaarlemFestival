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

    $sessionService->createSession($username, $password);
    $err = true; // TODO: catch exception when implemented
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
</head>
<body>
<form action="#" method="post">
    <input type="text" id="username" placeholder="Username" required autofocus name="username" value="<?php echo $username ?>" maxlength="96">
    <input type="password" id="password" placeholder="Password" required name="password" maxlength="72">
    <button class="btn btn-md btn-primary btn-block maxWidth">Sign in</button>
    <?php
    if (isset($err)){
        echo "<p class='mt-2 text-center text-danger txt-sm'>Failed to sign in.<br/>$err</p>";
    }
    ?>
</form>
</body>
</html>

