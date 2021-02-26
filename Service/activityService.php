<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once ($root . "/Service/foodactivityService.php");
require_once ($root . "/Service/danceActivityService.php");
require_once ($root . "/Service/jazzactivityService.php");
require_once ($root . "/DAL/activityDAO.php");
require_once ("baseService.php");


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

    public function getHtmlEditContent(int $id, account $account){
        $activities = [new foodactivityService(), new jazzactivityService(), new danceActivityService()];

        foreach ($activities as $a){
            try {
                return $a->getHtmlEditContent($id, $account);
            }
            catch (appException $e){
                // Do nothing
            }

        }

        return [];
    }
}