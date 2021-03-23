<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
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

$nav->assignCss([
        "sel" => "aSel"
]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>CMS - Events</title>
</head>

<body>
<script src="editMenu.js"></script>
<?php $nav->generate($user) ?>
<section class="main">
    <?php
        $event = $_GET["event"];
        if ($event == "jazz")
            $table = new jazzactivityService();
        elseif ($event == "dance")
            $table = new danceActivityService();
        elseif ($event == "food")
            $table = new foodactivityService();
        // TODO: make page to select events

        if (isset($_GET["err"])){
            $err = htmlspecialchars($_GET["err"], ENT_QUOTES);
            echo "<p class='err'>$err</p>";
        }

        if (isset($_GET["done"])){ // TODO: We should *really* post this
            $done = htmlspecialchars($_GET["done"], ENT_QUOTES);
            echo "<p class='done'>$done</p>";
        }

        if (isset($table)){
            $tables = $table->getTables($user, [
                "tr" => "cmsTableRow",
                "table" => "cmsTable",
                "h3" => "cmsTableHeader",
                "summary" => "cmsSummary",
                "details" => "cmsDetails",]);

            foreach ($tables as $t){
                $t->display();
            }
        }

    ?>

    <button onclick="openNew('<?php echo ucfirst($_GET["event"]) ?>')" type="button">Hey!</button>
    <button onclick="openSwap()" type="button">What</button>
    <button onclick="openDel()" type="button">Del</button>
    <button>Submit</button>
</section>
</body>
