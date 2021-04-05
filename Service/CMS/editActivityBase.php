<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once($root . "/Model/tableInterface.php");
require_once($root . "/Model/account.php");
require_once ($root . "/Service/baseService.php");
require_once ($root . "/Utils/appException.php");
require_once ($root . "/DAL/locationDAO.php");
require_once ("editInterface.php");
require_once ($root . "/Service/activityLogService.php");
require_once ("editBase.php");

// TODO: inherit from editBase
abstract class editActivityBase extends editBase
{
    protected activityBaseService $service;
    protected activityService $activityService;

    public function __construct(activityBaseService $service, account $account){
        parent::__construct($account);
        $this->service = $service;
        $this->activityService = new activityService();
    }

    public const htmlBaseEditHeader = [
        "activity" => [
            "activityId" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => [htmlTypeEnum::date, account::accountScheduleManager],
            "startTime" => [htmlTypeEnum::time, account::accountScheduleManager],
            "endTime" => [htmlTypeEnum::time, account::accountScheduleManager], // TODO: Maybe replace with length?
            "price" => [htmlTypeEnum::float, account::accountTicketManager],
            "ticketsLeft" => [htmlTypeEnum::number, account::accountTicketManager],
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

    public function getHtmlEditHeader(){
        return array_merge(static::htmlEditHeader, self::htmlBaseEditHeader);
    }

    public function getHtmlEditFields($entry){
        return array_merge($this->getHtmlBaseEditFields($entry), $this->getHtmlEditFieldsChild($entry));
    }

    public function getAllHtmlEditFieldsEmpty(){
        return array_merge($this->getHtmlEditFieldsEmpty(), $this->getHtmlBaseEditFieldsEmpty());
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

    public function getHtmlEditContent(int $id): array
    {
        $entries = $this->service->getFromActivityIds([$id]);
        if ($entries === [])
            throw new appException("Id not found");

        $entry = $entries[0];

        return $this->packHtmlEditContent($this->getHtmlEditFields($entry));
    }

    public function getHtmlEditContentEmpty(): array
    {
        if (($this->account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        return $this->packHtmlEditContent($this->getAllHtmlEditFieldsEmpty());
    }

    private function processAnyResponseLoc(array &$validatedPost){
        if (isset($validatedPost["activityIncomplete"]) || !isset($validatedPost["location"]))
            throw new appException("Activity is incomplete");

        $locationService = new locationService();

        // Updating the location table
        if (!isset($validatedPost["locationIncomplete"]) && (int)$validatedPost["location"] > 0) {
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
            if (isset($validatedPost["locationIncomplete"]))
                throw new appException("Location is incomplete");

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
    }

    // TODO: maybe parse POST into object, then use object?
    public function processEditResponse(array $post) {
        $validatedPost = $this->filterHtmlEditResponse($post);
        unset($post); // To prevent misuse

        $this->processAnyResponseLoc($validatedPost);

        if (isset($validatedPost["startTime"]) && isset($validatedPost["endTime"])){
            $startTime = (new time())->fromHI($validatedPost["startTime"]);
            $endTime = (new time())->fromHI($validatedPost["endTime"]);

            if ($startTime->getDateTime()->diff($endTime->getDateTime())->invert)
                $endTime = $startTime;
        }

        // Updating the activity table
        $activityService = new activityService();
        $activity = new activity();
        $activity->setId((int)$validatedPost["activityId"]);

        if (isset($validatedPost["date"]))
            $activity->setDate((new date())->fromYMD($validatedPost["date"])->getDateTime());

        if (isset($startTime)){
            $activity->setStartTime($startTime->getDateTime());
            $activity->setEndTime($endTime->getDateTime());
        }

        if (isset($validatedPost["price"]))
            $activity->setPrice((float)$validatedPost["price"]);

        if (isset($validatedPost["ticketsLeft"]))
            $activity->setTicketsLeft((int)$validatedPost["ticketsLeft"]);

        if (isset($validatedPost["locationIncomplete"])){
            $location = new location();
            $location->setId((int)$validatedPost["location"]);
            $activity->setLocation($location);
        }

        $activityService->updateActivityWithClass($activity);
        $this->processEditResponseChild($validatedPost);
        $this->createLog(activityLog::edit, (int)$validatedPost["activityId"]);
    }

    public function processNewResponse(array $post){
        if (($this->account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        $validatedPost = $this->filterHtmlEditResponse($post);
        unset($post); // To prevent misuse

        $this->processAnyResponseLoc($validatedPost);

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
        $this->createLog(activityLog::create, $id);
    }

    // TODO: get account on class creation
    public function processDeleteResponse(array $activityIds){
        if (($this->account->getCombinedRole() & (account::accountTicketManager | account::accountScheduleManager)) != (account::accountTicketManager | account::accountScheduleManager))
            throw new appException("Invalid permissions");

        $this->service->deleteTypedActivity($activityIds);
        $this->activityService->deleteActivity($activityIds);
        $this->createLog(activityLog::delete, null);
    }

    public const editType = "None";

    private function createLog(string $type, ?int $activityId){
        $log = new activityLog();
        $log->setAccount($this->account);
        $log->setType($type);

        if (!is_null($activityId)){
            $activity = new activity();
            $activity->setId($activityId);
            $log->setActivity($activity);
        }

        $logService = new activityLogService();
        $logService->insert($log);
    }

    public abstract function getHtmlEditFieldsChild(sqlModel $a) : array;
    public abstract function getHtmlEditFieldsEmpty();
    protected abstract function processEditResponseChild(array $validatedPost);
    protected abstract function processNewResponseChild(array $post, int $activityId);
}