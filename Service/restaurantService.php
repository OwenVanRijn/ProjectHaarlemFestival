<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("activityBaseService.php");
require_once($root . "/DAL/restaurantDAO.php");
require_once("restaurantTypeService.php");

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

    public function updateRestaurant(int $id, ?string $name, ?string $description, ?int $stars, ?int $seats, ?int $phoneNumber, float $price, int $locationId) : bool {
        $update = [
            "id" => $id,
        ];

        if (!is_null($name))
            $update["name"] = $name;

        if (!is_null($description))
            $update["description"] = $description;

        if (!is_null($stars))
            $update["stars"] = $stars;

        if (!is_null($seats))
            $update["seats"] = $seats;

        if (!is_null($phoneNumber))
            $update["phonenumber"] = $phoneNumber;

        if (!is_null($price))
            $update["price"] = $price;

        if (!is_null($locationId))
            $update["locationid"] = $locationId;

        return $this->db->update($update);
    }

    public function insertRestaurant(string $name, string $description, int $stars, int $seats, int $phoneNumber, float $price, int $locationId){
        $insert = [
            "name" => $name,
            "description" => $description,
            "stars" => $stars,
            "seats" => $seats,
            "phonenumber" => $phoneNumber,
            "price" => $price,
            "locationid" => $locationId
        ];

        return $this->db->insert($insert);
    }

    public function postEditFields(&$post)
    {
        if (isset($post["restaurantIncomplete"]) || $post["type"] != "Food" || !isset($post["location"]))
            return;

        $update = [
            "name" => $post["name"],
            "description" => $post["description"],
            "stars" => (int)$post["stars"],
            "seats" => (int)$post["seats"],
            "phonenumber" => (int)$post["phoneNumber"]
        ];

        if (isset($post["restaurantPrice"]))
            $update["price"] = (float)$post["restaurantPrice"];

        if (isset($post["restaurant"]) && (int)$post["restaurant"] == -1){
            $update["locationid"] = (int)$post["location"];

            if (!isset($update["price"]))
                throw new appException("Invalid permissions");

            $res = $this->db->insert($update);
            if (!$res)
                throw new appException("Db insert failed...");

            $update["id"] = $res;
            $post["restaurant"] = $res;
            $post["restaurantUpdated"] = true;
        }
        else {
            $update["id"] = (int)$post["restaurantId"];

            if (isset($post["locationIncomplete"])){
                $update["locationid"] = (int)$post["location"];
            }

            if (!$this->db->update($update))
                throw new appException("Db update failed...");
        }

        (new restaurantTypeService())->updateFieldIds($update["id"], $post["restaurantType"]);
    }

    public function getBySearch($searchTerm, $stars3, $stars4)
    {
        $filter = array();

        $filter = array_merge($filter, array("restaurant.name" => new dbContains($searchTerm)));

        if (!$stars3 || !$stars4) {
            $filter = array_merge($filter, array("restaurant.stars" => new dbContains(3)));
        }
        return $this->db->get($filter);
    }

    public function getById($id)
    {
        return $this->db->get([
            "restaurant.id" => new dbContains($id)
        ]);
    }

    public function getByType($type)
    {
        echo "TYPE IS $type";

        return $this->db->get([
            "restauranttypelink.restauranttypesid" => new dbContains($type)
        ]);
    }

    public function getAllRestaurantsAsStr(){
        $restaurants = $this->db->get();
        $restaurantStr = [];
        foreach ($restaurants as $b){
            $restaurantStr[(string)$b->getId()] = $b->getName();
        }
        return $restaurantStr;
    }
}