<?php

require_once ("sqlModel.php");

class location extends sqlModel
{
    private int $id;
    private int $activityId;
    private int $customerId;
    private int $orderId;
    private float $amount;

    protected const sqlTableName = "location";
    protected const sqlFields = ["id", "activityId", "customerId", "orderId", "amount"];

    public function constructFull(int $id, int $activityId, int $customerId, int $orderId, float $amount)
    {
        $this->id = $id;
        $this->activityId = $activityId;
        $this->customerId = $customerId;
        $this->orderId = $orderId;
        $this->amount = $amount;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "activityId" => $this->activityId,
            "customerId" => $this->customerId,
            "orderId" => $this->orderId,
            "amount" => $this->amount
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "activityId"],
            $sqlRes[self::sqlTableName . "customerId"],
            $sqlRes[self::sqlTableName . "orderId"],
            $sqlRes[self::sqlTableName . "amount"]
        );
    }

    public function getId() : int
    {
        return $this->id;
    }
}