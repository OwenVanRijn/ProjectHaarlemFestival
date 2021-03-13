<?php

require_once ("sqlModel.php");

class customerLocation extends sqlModel
{
    private int $id;
    private string $address;
    private string $postalcode;
    private string $city;
    protected const sqlTableName = "customerlocation";
    protected const sqlFields = ["id", "address", "postalCode", "city"];

    public function constructFull(int $id, string $address, string $postalcode, string $city)
    {
        $this->id = $id;
        $this->address = $address;
        $this->postalcode = $postalcode;
        $this->city = $city;
        return $this;
    }

    public function sqlGetFields()
    {
        return[
            "id" => $this->id,
            "address" => $this->address,
            "postalCode" => $this->postalcode,
            "city" => $this->city
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
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
