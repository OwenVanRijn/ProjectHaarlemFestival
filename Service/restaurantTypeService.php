<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/restaurantTypeLink.php");
require_once ("baseService.php");
require_once ($root . "/DAL/restaurantTypeLinkDAO.php");
require_once ($root . "/DAL/restaurantTypeDAO.php");

class restaurantTypeService extends baseService
{
    public function __construct(){
        $this->db = new restaurantTypeLinkDAO();
        $this->cache();
    }

    private array $cache; // Cache goes brr

    public function cache(){
        $this->cache = $this->db->get();
        // TODO: add sorting to possibly speed this up
    }

    private function getTypesFromId(int $id){
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

    public function getRestaurantTypesAsIds(int $restaurantId){
        $restaurants = [];

        foreach ($this->cache as $c){
            if ($c->getRestaurant()->getId() == $restaurantId)
                $restaurants[] = $c->getType()->getId();
        }

        return $restaurants;
    }

    public function getAllTypes(){
        $resTypeDAO = new restaurantTypeDAO();
        return $resTypeDAO->get();
    }

    public function getAllTypesAsStr(){
        $res = $this->getAllTypes();
        $strs = [];
        foreach ($res as $r){
            $strs[(string)$r->getId()] = $r->getName();
        }
        return $strs;
    }
}