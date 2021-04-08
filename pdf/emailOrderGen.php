<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "pdf.php";
include_once $root . "/Email/mailer.php";
include_once $root . "/Service/customerService.php";

class emailOrderGen
{
    function sendEmail($orderId, $customerId){
        $_SESSION['orderId'] = $orderId;

        $pdf = new pdf();
        $mailer = new mailer();
        $customer = new customerService();

        $ticketPdf = $pdf->loadTicketPDF(); //generates ticket pdf
        $invoicePdf = $pdf->loadInvoicePDF(); //generates invoice pdf

        file_put_contents( "pdf/invoice_".$orderId.".pdf", $invoicePdf); //saves pdf in pdf dir
        file_put_contents("pdf/tickets_".$orderId.".pdf", $ticketPdf);

        $email = $customer->getFromId($customerId)->getEmail(); //gets customer email from id

        //email with pdfs as attachment gets send
        $mailer->sendEmailWithAttachment($email, 'Your tickets for Haarlem Festival!',
        'Here are your tickets for Haarlem Festival from 26th until 29th of june', ['pdf/invoice_'.$orderId.'.pdf', 'pdf/tickets_'.$orderId.'.pdf']);

        $mailer->sendMail('louellacreemers@gmail.com', "TICKET SENDING SUCCESFUL", "customer $customerId got their tickets");
    }
}