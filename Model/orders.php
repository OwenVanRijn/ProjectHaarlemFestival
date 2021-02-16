<?php
require_once("sqlModel.php");

class jazzband extends sqlModel
{
    private int $id;
    private string $status;
    private int $customerId;


    protected const sqlTableName = "orders";
    protected const sqlFields = ["id", "status", "customerId"];


    public function constructFull(int $id, string $status, int $customerId)
    {
        $this->id = $id;
        $this->status = $status;
        $this->customerId = $customerId;
        return $this;
    }


    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "customerId" => $this->customerId
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "status"],
            $sqlRes[self::sqlTableName . "customerId"]
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


    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }
}
