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
        $artistDB = new dynamicQueryGen(danceArtist::class);

        return $artistDB->get();
    }
}