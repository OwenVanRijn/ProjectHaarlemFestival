<?php

require_once ("sqlModel.php");
require_once ("location.php");
require_once ("htmlTypeEnum.php");

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
            "location" => $this->location->getId(),
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

    public static function toHtmlHeader() {
        return [
            "id" => htmlTypeEnum::hidden,
            "type" => htmlTypeEnum::hidden,
            "date" => htmlTypeEnum::date,
            "startTime" => htmlTypeEnum::time,
            "endTime" => htmlTypeEnum::time,
            "price" => htmlTypeEnum::number,
            "ticketsLeft" => htmlTypeEnum::number
        ];
    }

    public function toHtmlValueArray() {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "date" => $this->date->format("d-m-Y"),
            "startTime" => $this->startTime->format("H:i:s"),
            "endTime" => $this->endTime->format("H:i:s"),
            // TODO: add location
            "price" => $this->price,
            "ticketsLeft" => $this->ticketsLeft
        ];
    }

    // TODO: make a filtered version of this?
    public function toHtmlArray(){
        return [
            "header" => self::toHtmlHeader(),
            "value" => $this->toHtmlValueArray()
        ];
    }
/*
    public function toJsonArray() {
        return [
            "activity" => [
                "id" => [
                    "type" => htmlTypeEnum::hidden,
                    "value" => $this->id
                ],
                "type" => [
                    "type" => htmlTypeEnum::hidden,
                    "value" => $this->type
                ],
                "date" => [
                    "type" => htmlTypeEnum::date,
                    "value" => $this->date->format("d-m-Y")
                ],
                "startTime" => [
                    "type" => htmlTypeEnum::time,
                    "value" => $this->startTime->format("H:i:s")
                ],
                "endTime" => [
                    "type" => htmlTypeEnum::time,
                    "value" => $this->endTime->format("H:i:s")
                ],
                // TODO: add location
                "price" => [ // TODO: schedule managers should not be able to see this
                    "type" => htmlTypeEnum::number,
                    "value" => $this->price
                ],
                "ticketsLeft" => [
                    "type" => htmlTypeEnum::number,
                    "value" => $this->ticketsLeft
                ]
            ]
        ];
    }
*/

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
}