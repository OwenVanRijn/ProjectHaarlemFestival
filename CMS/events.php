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
    <script src="https://kit.fontawesome.com/4cdba739f8.js" crossorigin="anonymous"></script>
    <title>CMS - Events</title>
</head>

<body>
<script src="editMenu.js"></script>
<?php $nav->generate($user) ?>
<section class="main">
    <?php
    if (isset($_GET["err"])){
        $err = htmlspecialchars($_GET["err"], ENT_QUOTES);
        echo "<p class='err'>$err</p>";
    }

    if (isset($_GET["done"])){ // TODO: We should *really* post this
        $done = htmlspecialchars($_GET["done"], ENT_QUOTES);
        echo "<p class='done'>$done</p>";
    }
    ?>

    <section class="displayBlock" id="topButtons">
        <?php if ($user->isTicketManager() && $user->isScheduleManager()) { ?>
            <button class="CMSTableButton" onclick="openNew('<?php echo ucfirst($_GET["event"]) ?>')" type="button"><i class="fas fa-plus-circle"></i> New event</button>
            <button class="CMSTableButton floatRight" onclick="openDel()" type="button"><i class="fas fa-trash-alt"></i> Delete Events</button>
        <?php }
        if ($user->isScheduleManager()) { ?>
            <button class="CMSTableButton" onclick="openSwap()" type="button"><i class="fas fa-sync"></i> Swap events</button>
        <?php } ?>
    </section>
    <section class="displayBlock submitCMSTopBar" id="confirmTopAction" hidden="">
        <button class="squareEscButton" onclick="removeCheckBoxes()" type="button"><i class="fas fa-times"></i></button>
        <button class="submitCMSTableButton" id="submitTop"><i class="fas fa-pen-square"></i> Save</button>
    </section>

    <?php
        $event = $_GET["event"];
        if ($event == "jazz")
            $table = new jazzactivityService();
        elseif ($event == "dance")
            $table = new danceActivityService();
        elseif ($event == "food")
            $table = new foodactivityService();
        // TODO: make page to select events

        if (isset($table)){
            $tables = $table->getTables($user, [
                "tr" => "cmsTableRow",
                "table" => "cmsTable",
                "h3" => "cmsTableHeader",
                "summary" => "cmsSummary",
                "details" => "cmsDetails",
                "button" => "blueButton pAll-half pSide-3"]);

            foreach ($tables as $t){
                $t->display();
            }
        }

    ?>
</section>
</body>
