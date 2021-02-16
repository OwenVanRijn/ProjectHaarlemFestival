<?php
require_once ("sqlModel.php");
require_once ("activity.php");

class danceActivity extends sqlModel
{
    private int $id;
    private activity $activity;
    private string $type;

    protected const sqlTableName = "danceActivity";
    protected const sqlFields = ["id", "activityid", "sessionType"];
    protected const sqlLinks = ["activityid" =>activity::class];

    public function __construct(int $id, activity $activity, string $type){
        $this->id = $id;
        $this->activity = $activity;
        $this->type = $type;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "activityid" => $this->activity->getId(),
            "sessionType" =>$this->activity,
        ];
    }
    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->__construct(
            $sqlRes[self::sqlTableName . "id"],
            activity::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "sessionType"]
        );
    }
    public function getId() : int
    {
        return $this->id;
    }
}