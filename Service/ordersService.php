<?php
require_once "./DAL/ordersDAO.php";

class ordersService
{
    public function __construct(){
        $this->db = new ordersDAO();
    }

    public function insertOrder(int $customerId){
        $this->db->insert([
            "status" => 2,
            "customerId" => $customerId
        ]);
    }

    public function getByCustomer(int $customerId){
        return $this->db->get(["customerId" => $customerId]);
    }

}