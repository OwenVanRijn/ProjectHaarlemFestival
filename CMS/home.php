<?php
    require_once("../Service/sessionService.php");
    $sessionService = new sessionService();

    $user = $sessionService->validateSessionFromCookie();

    if (!$user)
        header("Location: login.php");

    ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMS - Home</title>
</head>
<body>
    <p>Welcome, <?php echo $user->getUsername() ?></p>
    <a href="logout.php">Log out</a>
</body>