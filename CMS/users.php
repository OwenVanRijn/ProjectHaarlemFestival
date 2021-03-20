<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
require_once($root . "/UI/table.php");
require_once ($root . "/Service/customerService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user)
    header("Location: login.php");

//if (!($user->getCombinedRole() & account::accountTicketManager))
//    header("Location: home.php");

$nav = new navBarCMSGenerator("users.php");

$nav->assignCss([
    "sel" => "aSel"
]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>CMS - Users</title>
</head>

<body>
<script src="editMenu.js"></script>
<?php $nav->generate($user) ?>
<section class="main">
    <?php
        $customerService = new customerService();
        $customers = $customerService->getWithRole(0);

        if (is_null($customers)){
            $customers = [];
        }

        if (gettype($customers) != "array")
            $customers = [$customers];

        $table = new table();
        $table->setTitle("Users");
        $table->addHeader("Name", "Email", "Phone Number", "Address");

        foreach ($customers as $c){
            $tableRow = new tableRow();
            $tableRow->addString(
              $c->getFirstName() . " " . $c->getLastname(),
                $c->getAccount()->getEmail(),
                $c->getPhoneNumber(),
                $c->getLocation()->getAddress()
            );

            $customerId = $c->getId();

            $tableRow->addButton("openUser($customerId)", "Edit");
            $table->addTableRows($tableRow);
        }

        $table->assignCss([
            "tr" => "cmsTableRow",
            "table" => "cmsTable",
            "h3" => "cmsTableHeader",
            "summary" => "cmsSummary",
            "details" => "cmsDetails",]);

        $table->display();
    ?>
</section>
</body>