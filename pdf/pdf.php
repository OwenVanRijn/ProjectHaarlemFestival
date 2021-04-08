<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/lib/dompdf/autoload.inc.php";
require_once $root . "/Email/mailer.php";
use Dompdf\Dompdf;


class pdf{

    function loadTicketPDF(){
        $mailer = new mailer();

        $orderId = $_SESSION['orderId'];

        $mailer->sendMail("louellacreemers@gmail.com", "pdf.php", "Order= $orderId");

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


