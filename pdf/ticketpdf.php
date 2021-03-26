<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/Service/ticketService.php";
require_once $root . "/Service/activityService.php";
require_once $root . "/lib/barcodegen/vendor/autoload.php";

$activity = new activityService();
$ticket = new ticketService();
$id = $_SESSION['orderId'];
$returnTick = $ticket->getTicketsByOrder($id);
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

            if(is_object($returnTick)){
                echo "<section class = 'row' style='border-style: solid; margin: 2%; padding: 2%'>";
                echo "<section class='col-sm-10'>";
                $date = date_format($returnTick->getActivity()->getDate(), "d/m/y");
                $startTime = date_format($returnTick->getActivity()->getStartTime(), "H:i");
                $endTime = date_format($returnTick->getActivity()->getEndTime(), "H:i");
                $type = $returnTick->getActivity()->getType();
                $price = $returnTick->getActivity()->getPrice();
                $location = $returnTick->getActivity()->getLocation()->getName();

                echo "<p>{$type} - {$startTime} / {$endTime} @ {$date}. Location: {$location}, Price: {$price}EUR</p>";
                echo "</section>";

                echo "<section class='col-sm-2'>";
                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                echo $generator->getBarcode($returnTick->getId(), $generator::TYPE_CODE_128);
                echo "</section>";
                echo "</section>";
            }

            else{
                foreach ($returnTick as $item){
                    echo "<section class = 'row' style='border-style: solid; margin: 2%; padding: 2%'>";
                    echo "<section class='col-sm-10'>";
                    $date = date_format($item->getActivity()->getDate(), "d/m/y");
                    $startTime = date_format($item->getActivity()->getStartTime(), "H:i");
                    $endTime = date_format($item->getActivity()->getEndTime(), "H:i");
                    $type = $item->getActivity()->getType();
                    $price = $item->getActivity()->getPrice();
                    $location = $item->getActivity()->getLocation()->getName();

                    //$activityDetails = json_decode(json_encode($activity->getTypedActivityByIds([$item->getActivity()->getId()])), FALSE);
                    //print_r($activityDetails->getLocation());

                    echo "<p>{$type} - {$startTime} / {$endTime} @ {$date}. Location: {$location}, Price: {$price}EUR</p>";
                    echo "</section>";

                    echo "<section class='col-sm-2'>";
                    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                    echo $generator->getBarcode($item->getId(), $generator::TYPE_CODE_128);
                    echo "</section>";
                    echo "</section>";
                }
            }
            ?>
        </section>
    </body>
</html>
