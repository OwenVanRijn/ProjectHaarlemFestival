<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/locationDAO.php");

class locationService extends baseService
{
    public function __construct()
    {
        $this->db = new locationDAO();
    }

    public function postEditFields($post){
        if (isset($post["locationIncomplete"]))
            return;

        $update = [
            "id" => (int)$post["location"],
            "address" => $post["address"],
            "postalCode" => $post["postalCode"],
            "city" => $post["city"],
            "name" => $post["locationName"],
        ];

        if (!$this->db->update($update))
            throw new appException("Db update failed...");
    }
}