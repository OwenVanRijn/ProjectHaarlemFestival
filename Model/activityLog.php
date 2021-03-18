<?php
require_once ("sqlModel.php");
require_once ("account.php");
require_once ("activity.php");
require_once ("date.php");
require_once ("time.php");

class activityLog extends sqlModel
{
    private int $id;
    private string $type;
    private ?string $target;
    private ?account $account;
    private ?activity $activity;
    private date $date;
    private time $time;

    protected const sqlTableName = "activityLog";
    protected const sqlFields = ["id", "type", "target", "accountId", "activityId", "editDate", "editTime"];
    protected const sqlLinks = ["accountId" => account::class, "activityid" => activity::class];

    public function __construct(){
        $this->date = new date();
        $this->time = new time();
        $this->id = -1;
    }

    public const create = "created";
    public const edit = "edited";
    public const delete = "deleted";
    public const swap = "swapped";
    public const unkn = "unk";

    public function constructFull(int $id, string $type, ?string $target, ?account $account, ?activity $activity, date $date, time $time){
        $this->id = $id;
        $this->type = $type;
        $this->target = $target;
        $this->account = $account;
        $this->activity = $activity;
        $this->date = $date;
        $this->time = $time;
        return $this;
    }

    public function sqlGetFields()
    {
        $ret = [
            "type" => $this->type,
            "editDate" => $this->date,
            "editTime" => $this->time
        ];

        if ($this->id >= 0)
            $ret["id"] = $this->id;

        if (isset($this->account))
            $ret["accountId"] = $this->account->getId();

        if (isset($this->activity))
            $ret["activityId"] = $this->activity->getId();

        if (isset($this->target))
            $ret["target"] = $this->target;

        return $ret;
    }

    public static function sqlParse(array $sqlRes): sqlModel
    {
        $account = null;
        $activity = null;

        if (array_key_exists("activityId", $sqlRes))
            $activity = activity::sqlParse($sqlRes);

        if (array_key_exists("accountId", $sqlRes))
            $account = account::sqlParse($sqlRes);

        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "type"],
            $sqlRes[self::sqlTableName . "target"],
            $account,
            $activity,
            (new date)->fromYMD($sqlRes[self::sqlTableName . "editDate"]),
            (new time)->fromHIS($sqlRes[self::sqlTableName . "editTime"])
        );
    }

    public function getId()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return account|null
     */
    public function getAccount(): ?account
    {
        return $this->account;
    }

    /**
     * @return activity|null
     */
    public function getActivity(): ?activity
    {
        return $this->activity;
    }

    /**
     * @return date
     */
    public function getDate(): date
    {
        return $this->date;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string|null $target
     */
    public function setTarget(?string $target): void
    {
        $this->target = $target;
    }

    /**
     * @param account|null $account
     */
    public function setAccount(?account $account): void
    {
        $this->account = $account;
    }

    /**
     * @param activity|null $activity
     */
    public function setActivity(?activity $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @param date $date
     */
    public function setDate(date $date): void
    {
        $this->date = $date;
    }

    /**
     * @param time $time
     */
    public function setTime(time $time): void
    {
        $this->time = $time;
    }
}