<?php

require_once ("sqlModel.php");

class location extends sqlModel
{
    private int $id;
    private string $name;
    private string $address;
    private string $postalcode;
    private string $city;

    protected const sqlTableName = "location";
    protected const sqlFields = ["id", "name", "address", "postalCode", "city"];

    public function constructFull(int $id, string $name, string $address, string $postalcode, string $city)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->postalcode = $postalcode;
        $this->city = $city;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "postalCode" => $this->postalcode,
            "city" => $this->city
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "name"],
            $sqlRes[self::sqlTableName . "address"],
            $sqlRes[self::sqlTableName . "postalCode"],
            $sqlRes[self::sqlTableName . "city"]
        );
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPostalcode()
    {
        return $this->postalcode;
    }

    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    }


    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }
}
