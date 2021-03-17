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

    public function updateActivity(int $id, ?date $date, ?time $startTime, ?time $endTime, ?float $price, ?int $ticketsLeft, ?int $locationId){
        $update = [
            "id" => $id
        ];

        if (!is_null($date))
            $update["date"] = $date;

        if (!is_null($startTime))
            $update["startTime"] = $startTime;

        if (!is_null($endTime))
            $update["endTime"] = $endTime;

        if (!is_null($price))
            $update["price"] = $price;

        if (!is_null($ticketsLeft))
            $update["ticketsLeft"] = $ticketsLeft;

        if (!is_null($locationId))
            $update["locationId"] = $locationId;

        return $this->db->update($update);
    }

    public function insertActivity(string $type, date $date, time $startTime, time $endTime, float $price, int $ticketsLeft, int $locationId){
        $insert  = [
            "type" => $type,
            "date" => $date,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "price" => $price,
            "ticketsLeft" => $ticketsLeft,
            "locationId" => $locationId
        ];

        return $this->db->insert($insert);
    }

    public function swapActivityTime(int $activity1, int $activity2){
        $activities = $this->db->get([
                "id" => [$activity1, $activity2]
        ]);

        if (count($activities) != 2)
            throw new appException("One or more of the provided activities is invalid");

        $this->db->update([
            "id" => $activities[0]->getId(),
            "date" => $activities[1]->getDateAsDate(),
            "startTime" => $activities[1]->getStartTimeAsTime(),
            "endTime" => $activities[1]->getEndTimeAsTime()
        ]);

        $this->db->update([
            "id" => $activities[1]->getId(),
            "date" => $activities[0]->getDateAsDate(),
            "startTime" => $activities[0]->getStartTimeAsTime(),
            "endTime" => $activities[0]->getEndTimeAsTime()
        ]);
    }

    public function deleteActivity(array $activityIds){
        return $this->db->delete([
            "id" => $activityIds
        ]);
    }
}