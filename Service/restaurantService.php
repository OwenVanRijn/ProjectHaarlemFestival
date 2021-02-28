<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/restaurantDAO.php");
require_once ("restaurantTypeService.php");

class restaurantService extends baseService
{
    public function __construct()
    {
        $this->db = new restaurantDAO();
    }

    public function getAll(): array
    {
        return $this->db->get();
    }

    public function postEditFields($post){
        if (isset($post["restaurantIncomplete"]) || $post["type"] != "Food" || !isset($post["location"]))
            return;

        $update = [
            "id" => (int)$post["restaurantId"],
            "name" => $post["name"],
            "description" => $post["description"],
            "stars" => (int)$post["stars"],
            "seats" => (int)$post["seats"],
            "phonenumber" => (int)$post["phoneNumber"]
        ];

        if (isset($post["locationIncomplete"]))
            $update["locationId"] = (int)$post["location"];

        if (isset($post["restaurantPrice"]))
            $update["price"] = (float)$post["restaurantPrice"];

        (new restaurantTypeService())->updateFieldIds($update["id"], $post["restaurantType"]);

        if (!$this->db->update($update))
            throw new appException("Db update failed...");
    }
}