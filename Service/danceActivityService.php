<?php

require_once ("Model/artistOnActivity.php");
require_once ("DAL/dynamicQueryGen.php");
require_once ("baseService.php");


class danceActivityService extends baseService
{
    public function __construct()
    {
        parent::__construct(artistOnActivity::class);
    }

    public function getActivities(){
        $db = new dynamicQueryGen(artistOnActivity::class);

        return $db->get();
    }
}