<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once ("foodactivityService.php");
require_once ("danceActivityService.php");
require_once ("jazzactivityService.php");
require_once ($root . "/DAL/dbContains.php");
require_once ($root . "/DAL/activityDAO.php");
require_once ("restaurantService.php");
require_once ("locationService.php");
require_once ("baseService.php");
require_once ("activityLogService.php");

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

    public function updateActivity(int $id, ?date $date, ?time $startTime, ?time $endTime, ?float $price, ?int $ticketsLeft, ?int $locationId)
    {
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

    public function insertActivity(string $type, date $date, time $startTime, time $endTime, float $price, int $ticketsLeft, int $locationId)
    {
        $insert = [
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

    private function getNameFromTypedActivity($a, $inclClassName = false){
        $name = "";

        switch (get_class($a)){
            case "jazzactivity":
            case "danceActivity":
            case "foodactivity":
                $name = $a->getName();
                break;
            default:
                throw new appException("Invalid type provided");
        }

        if ($inclClassName)
            return get_class($a) . " " . $name;
        else
            return $name;
    }

    public function getNames(array $activityIds){
        $typedActivities = $this->getTypedActivityByIds($activityIds);
        $names = [];

        foreach ($typedActivities as $a){
            $names[$a->getActivity()->getId()] = $this->getNameFromTypedActivity($a, true);
        }

        return $names;
    }

    public function swapActivityTime(int $activity1, int $activity2, account $account){
        $typedActivities = $this->getTypedActivityByIds([$activity1, $activity2]);

        if (count($typedActivities) != 2)
            throw new appException("One or more of the provided activities is invalid");

        $activities = [$typedActivities[0]->getActivity(), $typedActivities[1]->getActivity()];

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

        $log = new activityLog();
        $log->setAccount($account);
        $log->setType(activityLog::swap);
        $log->setTarget($typedActivities[0]->getActivity()->getType() . " activities " . $this->getNameFromTypedActivity($typedActivities[0]) . " & " .
            $this->getNameFromTypedActivity($typedActivities[1]));

        $logService = new activityLogService();
        $logService->insert($log);
    }

    public function deleteActivity(array $activityIds)
    {
        return $this->db->delete([
            "id" => $activityIds
        ]);
    }

    public function getAll(): array
    {
        return $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"]
        ]);
    }

    public function getAllById($ids)
    {
        $activities = $this->db->get([
            "order" => ["activity.date", "activity.starttime", "activity.endtime"],
            "id" => $ids,
            "type" => new dbContains(["All-Access", "Dayticket"])
        ]);

        if (is_null($activities))
            return [];

        if (gettype($activities) != "array")
            return [$activities];

        return $activities;
    }
}