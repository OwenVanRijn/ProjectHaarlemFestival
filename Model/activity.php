<?php

require_once ("sqlModel.php");

class activity extends sqlModel
{
    private int $id;
    private string $type;
    private string $date;
    private string $startTime;
    private string $endTime;
    private location $location;
    private double $price;
    private int $ticketsLeft;

    protected const sqlTableName = "activity";
    protected const sqlFields = ["id", "type", "date", "startTime", "endTime", "locationId", "price", "ticketsLeft"];
    protected const sqlLinks = ["locationId" => location::class];

    public function __construct(int $id, string $type, string $date, string $startTime, string $endTime,
                                location $location, double $price, int $ticketsLeft){
        $this->id = $id;
        $this->type = $type;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->location = $location;
        $this->price = $price;
        $this->ticketsLeft = $ticketsLeft;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "type"=>$this->type,
            "date" => $this->date,
            "startTime" => $this->startTime,
            "endTime" => $this->endTime,
            "location" => $this->location->getId(),
            "price" =>$this->price,
            "ticketsLeft" =>$this->ticketsLeft
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->__construct(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "type"],
            $sqlRes[self::sqlTableName . "date"],
            $sqlRes[self::sqlTableName . "startTime"],
            $sqlRes[self::sqlTableName . "endTime"],
            location::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "price"],
            $sqlRes[self::sqlTableName . "ticketsLeft"]
        );
    }

    public function getId() : int
    {
        return $this->id;
    }
}