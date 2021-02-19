<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
require_once($root . "/UI/tableGenerator.php");
require_once ($root . "/Service/jazzactivityService.php");
require_once ($root . "/Service/foodactivityService.php");
require_once ($root . "/Service/danceActivityService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user)
    header("Location: login.php");

if (!isset($_GET["event"]))
    header("Location: home.php");

$nav = new navBarCMSGenerator("events.php?event=" . $_GET["event"]);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CMS - Events</title>
</head>
<body>
<?php $nav->generate($user) ?>
<section>
    <?php
        if ($_GET["event"] == "jazz")
            $table = new jazzactivityService();
        elseif ($_GET["event"] == "dance")
            $table = new danceActivityService();
        elseif ($_GET["event"] == "food")
            $table = new foodactivityService();
        // TODO: make page to select events

        if (isset($table)){
            $tableGen = new tableGenerator($table);
            $tableGen->generate();
        }
    ?>
</section>
</body>
