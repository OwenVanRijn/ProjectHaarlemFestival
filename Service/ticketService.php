<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ("./DAL/ticketDAO.php");


class ticketService extends  baseService
{
    public function __construct()
    {
        $this->db = new ticketDAO();
    }

    public function getTicketsByOrder(string $orderId){
        return $this->db->get([
            "ticket.orderId" => new dbContains("$orderId")
        ]);
    }

    public function insertTicket(int $activityId, int $customerId, int $orderId, int $amount){
        $this->db->insert([
            "activityId" => $activityId,
            "customerId" => $customerId,
            "orderId" => $orderId,
            "amount" => $amount
        ]);
    }
}