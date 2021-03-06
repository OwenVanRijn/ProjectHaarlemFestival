<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
    $sessionService = new sessionService();

    $user = $sessionService->validateSessionFromCookie();

    if (!$user)
        header("Location: login.php");

    $nav = new navBarCMSGenerator();

    $nav->assignCss([
        "sel" => "aSel"
    ]);

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>CMS - Home</title>
</head>
<body>
    <?php $nav->generate($user) ?>
    <section class="main">
        <p>Welcome, <?php echo $user->getUsername() ?></p>
    </section>
</body>