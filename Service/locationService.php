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
}