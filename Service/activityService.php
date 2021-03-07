<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once ("foodactivityService.php");
require_once ("danceActivityService.php");
require_once ("jazzactivityService.php");
require_once ($root . "/DAL/activityDAO.php");
require_once ("restaurantService.php");
require_once ("locationService.php");
require_once ("baseService.php");


class activityService extends baseService
{
    private jazzactivityService $jazz;
    private foodactivityService $food;
    private danceActivityService $dance;

    public function __construct()
    {
        $this->db = new activityDAO();
        $this->jazz = new jazzactivityService();
        $this->food = new foodactivityService();
        $this->dance = new danceActivityService();
    }

    public function getTypedActivityByIds(array $ids){
        return array_merge($this->jazz->getFromActivityIds($ids), $this->food->getFromActivityIds($ids), $this->dance->getFromActivityIds($ids));
    }

    public function getHtmlEditContent(int $id, account $account){
        $activities = [$this->food, $this->jazz, $this->dance];

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

    public function postEditFields($post){
        if (isset($post["activityIncomplete"]) || !isset($post["location"]))
            throw new appException("Activity not found in post request");

        $update = [
            "id" => (int)$post["activityId"]
        ];

        if (isset($post["date"]))
            $update["date"] = $post["date"];

        if (isset($post["startTime"]))
            $update["startTime"] = $post["startTime"];

        if (isset($post["endTime"]))
            $update["endTime"] = $post["endTime"];

        if (isset($post["price"]))
            $update["price"] = (float)$post["price"];

        if (isset($post["ticketsLeft"]))
            $update["ticketsLeft"] = (int)$post["ticketsLeft"];

        if (isset($post["locationIncomplete"]))
            $update["locationId"] = (int)$post["location"];

        if (!$this->db->update($update))
            throw new appException("Db update failed...");
    }

    public function writeHtmlEditFields($post, account $account){
        if (!isset($post["type"]))
            throw new appException("No data");

        switch ($post["type"]){
            case "Food":
                $newPost = $this->food->filterHtmlEditResponse($account, $post);
                break;
            case "Dance":
                $newPost = $this->dance->filterHtmlEditResponse($account, $post);
                break;
            case "Jazz":
                $newPost = $this->jazz->filterHtmlEditResponse($account, $post);
                break;
            default:
                throw new appException("An invalid type was requested");
        }

        $this->postEditFields($newPost);
        (new locationService())->postEditFields($newPost);
        (new restaurantService())->postEditFields($newPost);
        ($this->dance)->postEditFields($newPost);
        ($this->jazz)->postEditFields($newPost);

        echo json_encode($newPost);
    }
}