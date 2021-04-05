<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
require_once($root . "/UI/table.php");
require_once ($root . "/Service/artistService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user)
    header("Location: login.php");

if (!($user->getCombinedRole() & account::accountScheduleManager))
    header("Location: home.php");

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
    <title>CMS - Dance Artists</title>
</head>

<body>
<script src="editMenu.js"></script>
<?php $nav->generate($user) ?>
<section class="main">
    <?php
    $artistService = new artistService();
    $artists = $artistService->getArtists();

    $table = new table();
    $table->setTitle("Dance artists");
    $table->addHeader("Name");

    $table->assignCss([
        "tr" => "cmsTableRow",
        "table" => "cmsTable",
        "h3" => "cmsTableHeader",
        "summary" => "cmsSummary",
        "details" => "cmsDetails",
        "button" => "blueButton pAll-half pSide-3"]);

    foreach ($artists as $a){
        $tableRow = new tableRow();
        $table->addTableRows($tableRow);
        $tableRow->addString($a->getName());

        $artistId = $a->getId();
        $tableRow->addButton("openDanceArtist($artistId)", "Edit");
    }


    $table->display();
    ?>
</section>
</body>