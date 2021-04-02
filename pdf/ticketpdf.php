<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/Service/ticketService.php";
require_once $root . "/Service/activityService.php";
require_once $root . "/lib/barcodegen/vendor/autoload.php";

$activity = new activityService();
$ticket = new ticketService();
$id = $_SESSION['orderId'];
$ticketArray = $ticket->getTicketsByOrder($id);
?>

<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css";
    </head>

    <body>
        <section class="text-center">
            <h1>HAARLEM FESTIVAL TICKET(S)</h1>
        </section>

        <section class="container">
            <?php
                foreach ($ticketArray as $item){
                    echo "<section class = 'row' style='border-style: solid; margin: 2%; padding: 2%'>";
                    echo "<section class='col-sm-10'>";
                    $date = date_format($item->getActivity()->getDate(), "d/m/y");
                    $startTime = date_format($item->getActivity()->getStartTime(), "H:i");
                    $endTime = date_format($item->getActivity()->getEndTime(), "H:i");
                    $type = $item->getActivity()->getType();
                    $price = $item->getActivity()->getPrice();
                    $location = $item->getActivity()->getLocation()->getName();

                    echo "<p>{$type} - {$startTime} / {$endTime} @ {$date}. Location: {$location}, Price: {$price}EUR</p>";
                    echo "</section>";

                    echo "<section class='col-sm-2'>";
                    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                    echo $generator->getBarcode($item->getId(), $generator::TYPE_CODE_128);
                    echo "</section>";
                    echo "</section>";
                }
            ?>
        </section>
    </body>
</html>
