<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/artistOnActivityDAO.php");
require_once ($root . "/DAL/danceArtistDAO.php");
require_once ($root . "/Service/danceArtistService.php");

class artistOnActivityService extends baseService
{
    private danceActivityService $activity;

    public function __construct(){
        $this->db = new artistOnActivityDAO();
        $this->activity = new danceActivityService();
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
        $ar = $this->db->get([
            "danceartist.name" => new dbContains($artist)
        ]);

        return $this->activity->toDanceActivityArray($ar);
    }

    public function getBySessionAndArtist($artist, $session){
        $ar = $this->db->getArray([
            "danceartist.name" => $artist,
            "danceactivity.sessionType" => $session
        ]);

        return $this->activity->toDanceActivityArray($ar);
    }
    public function getActivityById($danceActivityId){
        return $this->db->get([
            "danceactivityid" => $danceActivityId
        ]);
    }
}