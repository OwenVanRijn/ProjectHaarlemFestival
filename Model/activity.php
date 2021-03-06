<?php

require_once ("sqlModel.php");
require_once ("location.php");
require_once ("htmlTypeEnum.php");
require_once ("sqlModel.php");
require_once ("date.php");
require_once ("time.php");

class activity extends sqlModel
{
    private int $id;
    private string $type;
    private DateTime $date;
    private DateTime $startTime;
    private DateTime $endTime;
    private location $location;
    private float $price;
    private int $ticketsLeft;

    protected const sqlTableName = "activity";
    protected const sqlFields = ["id", "type", "date", "startTime", "endTime", "locationId", "price", "ticketsLeft"];
    protected const sqlLinks = ["locationId" => location::class];

    public function __construct(){
        $this->ticketsLeft = 0;
    }

    public function constructFull(int $id, string $type, DateTime $date, DateTime $startTime, DateTime $endTime,
                                location $location, float $price, $ticketsLeft){
        $this->id = $id;
        $this->type = $type;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->location = $location;
        $this->price = $price;
        if (!is_null($ticketsLeft))
            $this->ticketsLeft = $ticketsLeft;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "type"=>$this->type,
            "date" => $this->date,
            "startTime" => $this->startTime->format("H:i:s"),
            "endTime" => $this->endTime->format("H:i:s"),
            "locationId" => $this->location->getId(),
            "price" =>$this->price,
            "ticketsLeft" =>$this->ticketsLeft
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "type"],
            date_create_from_format("Y-m-d", $sqlRes[self::sqlTableName . "date"]),
            date_create_from_format("H:i:s", $sqlRes[self::sqlTableName . "startTime"]),
            date_create_from_format("H:i:s", $sqlRes[self::sqlTableName . "endTime"]),
            location::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "price"],
            $sqlRes[self::sqlTableName . "ticketsLeft"]
        );
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return DateTime
     */
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    /**
     * @return DateTime
     */
    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    /**
     * @return location
     */
    public function getLocation(): location
    {
        return $this->location;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getTicketsLeft(): int
    {
        return $this->ticketsLeft;
    }

    public function getDateAsDate(): date {
        $date = new date();
        $date->fromDateTime($this->date);
        return $date;
    }

    public function getStartTimeAsTime() : time {
        $time = new time();
        $time->fromDateTime($this->startTime);
        return $time;
    }

    public function getEndTimeAsTime() : time {
        $time = new time();
        $time->fromDateTime($this->endTime);
        return $time;
    }
}