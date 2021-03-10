<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/danceActivityDAO.php");

class danceArtistService extends baseService
{
    public function __construct(){
        $this->db = new danceActivityDAO();
    }

    public function getAllAsStr(){
        $allArtists = $this->db->get();
        $artistStrs = [];
        foreach ($allArtists as $b){
            $artistStrs[(string)$b->getId()] = $b->getName();
        }
        return $artistStrs;
    }
}