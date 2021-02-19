<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("activityBaseService.php");
require_once ($root . "/DAL/jazzactivityDAO.php");

class jazzactivityService extends activityBaseService
{
    public function __construct()
    {
        $this->db = new jazzactivityDAO();
    }

    public function getFields() : array {
        return [
            "Name" => function ($a){
                return $a->getJazzband()->getName();
            },
            "Location" => function ($a){
                return $a->getHall();
            }
        ];
    }

    public function getAll(): array
    {
        return $this->db->get();
    }
}