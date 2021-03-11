<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/artistOnActivityDAO.php");
require_once ($root . "/DAL/danceArtistDAO.php");

class artistOnActivityService extends baseService
{
    public function __construct(){
        $this->db = new artistOnActivityDAO();
    }

    public function updateArtistIds(int $activityId, array $artistIds){
        $this->db->delete([
            "danceactivityid" => $activityId
        ]);

        // TODO: maybe merge call?
        foreach ($artistIds as $id){
            $this->db->insert([
                "danceactivityid" => $activityId,
                "danceartistid" => (int)$id
            ]);
        }
    }

    public function getActivityByArtist($artist){
        $aoa = new artistOnActivityDAO();
        return $aoa->get([
            "danceartist.name" => new dbContains($artist)
        ]);
    }
}