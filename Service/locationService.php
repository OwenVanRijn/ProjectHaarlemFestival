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

    public function updateLocation(int $id, ?string $address, ?string $postalCode, ?string $city, ?string $name){
        $update = [
            "id" => $id
        ];

        if (!is_null($address))
            $update["address"] = $address;

        if (!is_null($postalCode))
            $update["postalCode"] = $postalCode;

        if (!is_null($city))
            $update["city"] = $city;

        if (!is_null($name))
            $update["name"] = $name;

        return $this->db->update($update);
    }

    public function insertLocation(string $address, string $postalCode, string $city, string $name){
        $insert = [
            "address" => $address,
            "postalCode" => $postalCode,
            "city" => $city,
            "name" => $name
        ];

        return $this->db->insert($insert);
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