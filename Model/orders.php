<?php
require_once("sqlModel.php");
require_once("customer.php");

class orders extends sqlModel
{
    private int $id;
    private string $status;
    private customer $customer;

    protected const sqlTableName = "orders";
    protected const sqlFields = ["id", "status", "customerid"];
    protected const sqlLinks = ["customerid" => customer::class];

    public function constructFull(int $id, string $status, customer $customer)
    {
        $this->id = $id;
        $this->status = $status;
        $this->customer = $customer;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "customerid" => $this->customer->getId()
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "status"],
            customer::sqlParse($sqlRes)
        );
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
