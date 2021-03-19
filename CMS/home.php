<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
require_once($root . "/Service/activityLogService.php");
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
        <h3 class="mt-5">Recent edits</h3>
        <?php
            $logService = new activityLogService();
            $logs = $logService->getWithName(10);
            foreach ($logs as $l){
                $acc = $l->getAccount();
                if (is_null($acc))
                    $username = "[Deleted account]";
                else
                    $username = $acc->getUsername();
                $type = $l->getType();
                $target = $l->getTarget();
                echo "<span>$username $type $target</span>";
            }
        ?>
    </section>
</body>