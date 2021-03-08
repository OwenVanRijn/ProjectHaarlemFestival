<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/jazzbandDAO.php");

class jazzBandService extends baseService
{
    public function __construct(){
        $this->db = new jazzbandDAO();
    }

    public function updateBand(int $bandId, string $bandName, string $bandDesc){
        $this->db->update([
            "id" => $bandId,
            "name" => $bandName,
            "description" => $bandDesc
        ]);
    }

    public function insertBand(string $bandName, string $bandDesc) : int {
        return $this->db->insert([
            "name" => $bandName,
            "description" => $bandDesc
        ]);
    }
}