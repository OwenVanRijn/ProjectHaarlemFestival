<?php
require_once "../DAL/ordersDAO.php";

class ordersService
{
    public function __construct(){
        $this->db = new ordersDAO();
    }

    public function insertOrder(){
        $this->db->insert([
            "status" => 1,
        ]);
    }

}