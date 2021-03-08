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

    public function postEditFields(&$post){
        if (isset($post["locationIncomplete"]))
            return;

        $update = [
            "address" => $post["address"],
            "postalCode" => $post["postalCode"],
            "city" => $post["city"],
            "name" => $post["locationName"],
        ];

        if ((int)$post["location"] == -1){
            $res = $this->db->insert($update);
            if (!$res)
                throw new appException("Db insert failed...");

            $post["locationIncomplete"] = true;
            $post["location"] = $res;
        }
        else {
            $update["id"] = (int)$post["location"];

            if (!$this->db->update($update))
                throw new appException("Db update failed...");
        }
    }
}