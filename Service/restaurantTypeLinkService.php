<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/restaurantTypeLink.php");
require_once($root . "/Model/restaurant.php");
require_once($root . "/Service/baseService.php");
require_once($root . "/DAL/dbContains.php");
require_once($root . "/DAL/restaurantTypeLinkDAO.php");
require_once($root . "/DAL/restaurantTypeDAO.php");

class restaurantTypeLinkService extends baseService
{
    public function __construct()
    {
        $this->db = new restaurantTypeLinkDAO();
    }

    private array $cache; // Cache goes brr

    public function cache()
    {
        $this->cache = $this->db->get();
        // TODO: add sorting to possibly speed this up
    }

    private function getTypesFromId(int $id)
    {
        if (!isset($this->cache))
            $this->cache();

        $restaurants = [];

        foreach ($this->cache as $c) {
            if ($c->getRestaurant()->getId() == $id)
                $restaurants[] = $c->getType()->getName();
        }

        return $restaurants;
    }

    public function getRestaurantTypes(int $restaurantId)
    {
        return $this->getTypesFromId($restaurantId);
    }

    public function getRestaurantTypesAsIds(int $restaurantId)
    {
        if (!isset($this->cache))
            $this->cache();

        $restaurants = [];

        foreach ($this->cache as $c) {
            if ($c->getRestaurant()->getId() == $restaurantId)
                $restaurants[] = $c->getType()->getId();
        }

        return $restaurants;
    }

    public function getAllTypes()
    {
        $resTypeDAO = new restaurantTypeDAO();
        return $resTypeDAO->get([
            "order" => "name"
        ]);
    }

    public function getAllTypesAsStr()
    {
        $res = $this->getAllTypes();
        $strs = [];
        foreach ($res as $r) {
            $strs[(string)$r->getId()] = $r->getName();
        }
        return $strs;
    }

    public function updateFieldIds(int $restaurantId, array $typeIds)
    {
        $this->db->delete([
            "restaurantid" => $restaurantId
        ]);

        // TODO: maybe merge call?
        foreach ($typeIds as $id) {
            $this->db->insert([
                "restaurantid" => $restaurantId,
                "restauranttypesid" => (int)$id
            ]);
        }
    }


    public function getBySearch($typeID, $searchTerm, $stars3, $stars4)
    {
        $filter = array();

        $filter = array_merge($filter, array("restaurant.name" => new dbContains($searchTerm)));

        $stars = array();
        if ($stars3) {
            $stars[] = "3";
        }

        if ($stars4) {
            $stars[] = "4";
        }
        if (count($stars) > 0) {
            $filter = array_merge($filter, array("restaurant.stars" => $stars));
        }

        if ($typeID > 0) {
            $filter = array_merge($filter, array("restauranttypes.id" => $typeID));
        }

        $restaurantTypeLinks = $this->db->get($filter);

        return $this->getRestaurants($restaurantTypeLinks);
    }


    public function getByType($typeID)
    {
        if ($typeID > 0) {
            $restaurantTypeLinks = $this->db->get(["restauranttypes.id" => $typeID]);
        } else {
            $restaurantTypeLinks = $this->db->get();
        }

        return $this->getRestaurants($restaurantTypeLinks);
    }

    function getRestaurants($restaurantTypeLinks)
    {
        if ($restaurantTypeLinks == null) {
            return null;
        }

        $restaurants = array();
        if (is_array($restaurantTypeLinks)) {
            foreach ($restaurantTypeLinks as $restaurantTypeLink) {
                $restaurant = $restaurantTypeLink->getRestaurant();

                if ($this->checkDuplicate($restaurants, $restaurant->getId())) {
                    $restaurants[] = $restaurant;
                }
            }
        } else {
            $restaurant = $restaurantTypeLinks->getRestaurant();
            $restaurants[] = $restaurant;
        }
        return $restaurants;
    }

    private function checkDuplicate($restaurants, $restaurantId)
    {
        foreach ($restaurants as $restaurant) {
            if ($restaurant->getId() == $restaurantId) {
                return 0;
            }
        }
        return 1;
    }

}