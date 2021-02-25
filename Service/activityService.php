<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once ($root . "/DAL/foodactivityDAO.php");
require_once ($root . "/DAL/danceActivityDAO.php");
require_once ($root . "/DAL/jazzactivityDAO.php");
require_once ($root . "/DAL/activityDAO.php");


class activityService extends baseService
{
    public function __construct()
    {
        $this->db = new activityDAO();
    }

    public function getTypedActivityByIds(array $ids){
        $jazz = new jazzactivityService();
        $food = new foodactivityService();
        $dance = new danceActivityService();

        return array_merge($jazz->getFromActivityIds($ids), $food->getFromActivityIds($ids), $dance->getFromActivityIds($ids));
    }
}