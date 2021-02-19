<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/restaurantTypeLink.php");
require_once ("baseService.php");
require_once ($root . "/DAL/restaurantTypeLinkDAO.php");

class restaurantTypeService extends baseService
{
    public function __construct(){
        $this->db = new restaurantTypeLinkDAO();
    }

    private array $cache; // Cache goes brr

    public function cache(){
        $this->cache = $this->db->get();
        // TODO: add sorting to possibly speed this up
    }

    private function getTypesFromId(int $id){
        if (!isset($this->cache))
            $this->cache();

        $restaurants = [];

        foreach ($this->cache as $c){
            if ($c->getRestaurant()->getId() == $id)
                $restaurants[] = $c->getType()->getName();
        }

        return $restaurants;
    }

    public function getRestaurantTypes(int $restaurantId){
        return $this->getTypesFromId($restaurantId);
    }
}