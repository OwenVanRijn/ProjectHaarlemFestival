<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");
require_once ("editInterface.php");
require_once ($root . "/Service/activityLogService.php");

// TODO: inherit from editBase
abstract class editActivityBase implements editInterface
{
    protected activityBaseService $service;
    protected activityService $activityService;

    public function __construct(activityBaseService $service){
        $this->service = $service;
        $this->activityService = new activityService();
    }

    public const htmlBaseEditHeader = [
        "activity" => [
            "activityId" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => [htmlTypeEnum::date, account::accountScheduleManager],
            "startTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "endTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "price" => [htmlTypeEnum::float, account::accountTicketManager],
            "ticketsLeft" => [htmlTypeEnum::number, account::accountTicketManager]
        ],
        "location" => [
            "locationName" => htmlTypeEnum::text,
            "location" => htmlTypeEnum::list, //we need a way to GET the location's details
            "address" => htmlTypeEnum::text,
            "postalCode" => htmlTypeEnum::text,
            "city" => htmlTypeEnum::text
        ]
    ];

    public function getHtmlBaseEditFields($a): array
    {
        $locationDAO = new locationDAO();
        $locations = $locationDAO->get();
        $locationStrings = [];
        foreach ($locations as $l){
            $locationStrings[(string)$l->getId()] = $l->getName();
        }

        return [
            "activityId" => $a->getActivity()->getId(),
            "type" => $a->getActivity()->getType(),
            "date" => $a->getActivity()->getDate()->format("Y-m-d"),
            "startTime" => $a->getActivity()->getStartTime()->format("H:i"),
            "endTime" => $a->getActivity()->getEndTime()->format("H:i"),
            "price" => $a->getActivity()->getPrice(),
            "ticketsLeft" => $a->getActivity()->getTicketsLeft(),
            "address" => $a->getActivity()->getLocation()->getAddress(),
            "postalCode" => $a->getActivity()->getLocation()->getPostalcode(),
            "city" => $a->getActivity()->getLocation()->getCity(),
            "location" => [
                "options" => $locationStrings,
                "selected" => $a->getActivity()->getLocation()->getId()
            ],
            "locationName" => $a->getActivity()->getLocation()->getName()
        ];
    }

    public function getHtmlBaseEditFieldsEmpty(){
        $locationDAO = new locationDAO();
        $locations = $locationDAO->get();
        $locationStrings = [];
        foreach ($locations as $l){
            $locationStrings[(string)$l->getId()] = $l->getName();
        }

        return [
            "activityId" => "new",
            "type" => static::editType,
            "date" => "",
            "startTime" => "",
            "endTime" => "",
            "price" => "",
            "ticketsLeft" => "",
            "address" => "",
            "postalCode" => "",
            "city" => "",
            "location" => [
                "options" => $locationStrings,
                "selected" => "-1"
            ],
            "locationName" => ""
        ];
    }

    private function stripHtmlChars($input){
        switch (gettype($input)){
            case "string":
                if (empty($input))
                    throw new appException("Empty string provided!");

                return trim(htmlspecialchars($input, ENT_QUOTES));
            case "array":
                $new = [];
                foreach ($input as $a){
                    $new[] = $this->stripHtmlChars($a);
                }
                return $new;
            default:
                throw new appException("Can't strip type " . gettype($input));
        }
    }

    public function filterHtmlEditResponse(account $account, array $postResonse){
        $header = array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
        $correctedPostResponse = [];

        foreach ($header as $hk => $hv){
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        if (array_key_exists($k, $postResonse))
                            $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                        else
                            $correctedPostResponse[$hk . "Incomplete"] = true;
                }
                elseif (array_key_exists($k, $postResonse))
                    $correctedPostResponse[$k] = $this->stripHtmlChars($postResonse[$k]);
                else
                    $correctedPostResponse[$hk . "Incomplete"] = true;
            }
        }

        return $correctedPostResponse;
    }

    public function getHtmlEditContent(int $id, account $account): array
    {
        $entries = $this->service->getFromActivityIds([$id]);
        if ($entries === [])
            throw new appException("Id not found");

        $entry = $entries[0];
        $header = array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
        $fields = array_merge($this->getHtmlEditFields($entry), $this->getHtmlBaseEditFields($entry));

        $res = [];
        foreach ($header as $hk => $hv){
            $classField = [];
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        $classField[$k] = ["type" => $v[0], "value" => $fields[$k]];
                }
                else
                    $classField[$k] = ["type" => $v, "value" => $fields[$k]];
            }
            $res[$hk] = $classField;
        }

        return $res;
    }

    public function getHtmlEditContentEmpty(account $account): array
    {
        if (($account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        $header = array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
        $fields = array_merge($this->getHtmlEditFieldsEmpty(), $this->getHtmlBaseEditFieldsEmpty());

        $res = [];
        foreach ($header as $hk => $hv){
            $classField = [];
            foreach ($hv as $k => $v){
                if (gettype($v) == "array"){
                    if (($account->getCombinedRole() & $v[1]))
                        $classField[$k] = ["type" => $v[0], "value" => $fields[$k]];
                }
                else
                    $classField[$k] = ["type" => $v, "value" => $fields[$k]];
            }
            $res[$hk] = $classField;
        }

        return $res;
    }

    // TODO: maybe parse POST into object, then use object?
    public function processEditResponse(array $post, account $account) {
        $validatedPost = $this->filterHtmlEditResponse($account, $post);
        unset($post); // To prevent misuse

        if (isset($validatedPost["activityIncomplete"]) || !isset($validatedPost["location"]))
            throw new appException("Activity not found in post request");


        $locationService = new locationService();

        // Updating the location table
        if (!isset($validatedPost["locationIncomplete"])) {
            if (!$locationService->updateLocation(
                $validatedPost["location"],
                $validatedPost["address"],
                $validatedPost["postalCode"],
                $validatedPost["city"],
                $validatedPost["locationName"]
            ))
                throw new appException("[Location] db update failed... ");
        }

        if ((int)$validatedPost["location"] == -1){
            $res = $locationService->insertLocation(
                $validatedPost["address"],
                $validatedPost["postalCode"],
                $validatedPost["city"],
                $validatedPost["locationName"]
            );

            if (!$res)
                throw new appException("[Location] db insert failed...");

            $validatedPost["locationIncomplete"] = true;
            $validatedPost["location"] = $res;
        }


        // Updating the activity table
        $activityService = new activityService();
        $activityService->updateActivity(
            (int)$validatedPost["activityId"],
            (new date())->fromYMD($validatedPost["date"]),
            (new time())->fromHI($validatedPost["startTime"]),
            (new time())->fromHI($validatedPost["endTime"]),
            (isset($validatedPost["price"])) ? (float)$validatedPost["price"] : null,
            (isset($validatedPost["ticketsLeft"])) ? (int)$validatedPost["ticketsLeft"] : null,
            (isset($validatedPost["locationIncomplete"])) ? (int)$validatedPost["location"] : null);

        $this->processEditResponseChild($validatedPost);
        $this->createLog(activityLog::edit, (int)$validatedPost["activityId"], $account);
    }

    public function processNewResponse(array $post, account $account){
        if (($account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        $validatedPost = $this->filterHtmlEditResponse($account, $post);
        unset($post); // To prevent misuse

        if (isset($validatedPost["activityIncomplete"]) || !isset($validatedPost["location"]))
            throw new appException("Activity not found in post request");

        $locationService = new locationService();

        // Updating the location table
        if (!isset($validatedPost["locationIncomplete"])) {
            if (!$locationService->updateLocation(
                $validatedPost["location"],
                $validatedPost["address"],
                $validatedPost["postalCode"],
                $validatedPost["city"],
                $validatedPost["locationName"]
            ))
                throw new appException("[Location] db update failed... ");
        }

        if ((int)$validatedPost["location"] == -1){
            $res = $locationService->insertLocation(
                $validatedPost["address"],
                $validatedPost["postalCode"],
                $validatedPost["city"],
                $validatedPost["locationName"]
            );

            if (!$res)
                throw new appException("[Location] db insert failed...");

            $validatedPost["locationIncomplete"] = true;
            $validatedPost["location"] = $res;
        }

        $activityService = new activityService();
        $id = $activityService->insertActivity(
            static::editType,
            (new date())->fromYMD($validatedPost["date"]),
            (new time())->fromHI($validatedPost["startTime"]),
            (new time())->fromHI($validatedPost["endTime"]),
            (float)$validatedPost["price"],
            (int)$validatedPost["ticketsLeft"],
            (int)$validatedPost["location"]);

        $this->processNewResponseChild($validatedPost, $id);
        $this->createLog(activityLog::create, $id, $account);
    }

    // TODO: get account on class creation
    public function processDeleteResponse(array $activityIds, account $account){
        if (($account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        $this->service->deleteTypedActivity($activityIds);
        $this->activityService->deleteActivity($activityIds);
        $this->createLog(activityLog::delete, null, $account);
    }

    public const htmlEditHeader = [];
    public const editType = "None";

    private function createLog(string $type, ?int $activityId, account $account){
        $log = new activityLog();
        $log->setAccount($account);
        $log->setType($type);

        if (!is_null($activityId)){
            $activity = new activity();
            $activity->setId($activityId);
            $log->setActivity($activity);
        }

        $logService = new activityLogService();
        $logService->insert($log);
    }

    public abstract function getHtmlEditFields(sqlModel $a) : array;
    protected abstract function processEditResponseChild(array $validatedPost);
    protected abstract function processNewResponseChild(array $post, int $activityId);
}