<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/danceArtistDAO.php");

class danceArtistService extends baseService
{
    public function __construct(){
        $this->db = new danceArtistDAO();
    }

    public function getAllAsStr(){
        $allArtists = $this->db->get();
        $artistStrs = [];
        foreach ($allArtists as $b){
            $artistStrs[(string)$b->getId()] = $b->getName();
        }
        return $artistStrs;
    }

    public function getFromId(int $id){
        return $this->db->get(["id" => $id]);
    }
}