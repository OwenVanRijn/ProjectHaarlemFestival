<?php

require_once("sqlModel.php");
require_once("activity.php");
require_once("customer.php");
require_once("orders.php");

class ticket extends sqlModel
{
    private int $id;
    private activity $activity;
    private customer $customer;
    private orders $order;
    private float $amount;

    protected const sqlTableName = "location";
    protected const sqlFields = ["id", "activityId", "customerId", "orderId", "amount"];
    protected const sqlLinks = [
        "activityId" => activity::class,
        "customerId" => customer::class,
        "orderId" => orders::class
    ];

    public function constructFull(int $id, activity $activity, customer $customer, orders $order, float $amount)
    {
        $this->id = $id;
        $this->activity = $activity;
        $this->customer = $customer;
        $this->order = $order;
        $this->amount = $amount;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "activityId" => $this->activity->getId(),
            "customerId" => $this->customer->getId(),
            "orderId" => $this->order->getId(),
            "amount" => $this->amount
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            activity::sqlParse($sqlRes),
            customer::sqlParse($sqlRes),
            orders::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "amount"]
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getActivity(): activity
    {
        return $this->activity;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
