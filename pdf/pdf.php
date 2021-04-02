<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/lib/dompdf/autoload.inc.php";
use Dompdf\Dompdf;


class pdf{

    function loadTicketPDF(){
        $orderId = $_SESSION['orderId'];

        ob_start();
        include 'ticketpdf.php';
        $rawHtml = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($rawHtml);

        $dompdf->render();

        return $output = $dompdf->output();

    }

    function loadInvoicePDF(){
        ob_start();
        include 'invoicepdf.php';
        $rawHtml = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($rawHtml);

        $dompdf->render();
        return $dompdf->output();
    }
}



//
//// (Optional) Setup the paper size and orientation
//$dompdf->setPaper('A4', 'landscape');
//
//// Render the HTML as PDF
//$dompdf->render();
//
//// Output the generated PDF to Browser
//$dompdf->stream();
?>


