<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("Model/danceArtist.php");
require_once ("DAL/dynamicQueryGen.php");
require_once ("baseService.php");

class artistService extends baseService
{
    public function __construct()
    {
        parent::__construct(danceArtist::class);
    }

    public function getArtists(){
        $artistDB = new dynamicQueryGen(danceArtist::class);

        return $artistDB->get();
    }
}