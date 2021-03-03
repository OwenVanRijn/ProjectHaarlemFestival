<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ($root . "/Model/danceArtist.php");
require_once ($root . "/DAL/danceArtistDAO.php");
require_once ("baseService.php");

class artistService extends baseService
{
    public function __construct()
    {
        $this->db = new danceArtistDAO();
    }

    public function getArtists(){
        return $this->db->get();
    }

    public function getArtist(string $name){
        $res = $this->db->get()([
           "danceArtist.name" => $name,
            "order" => ["danceArtist.id", "danceArtist.name", "danceArtist.description"]
        ]);

        print_r($res);
    }
}