<?php

require_once ("sqlModel.php");

class customer extends sqlModel
{
    private int $id;
    private string $firstName;
    private string $lastname;
    private int $locationId;
    private int $phoneNumber;
    private int $accountId;

    protected const sqlTableName = "customer";
    protected const sqlFields = ["id", "firstName", "lastname", "locationId", "phoneNumber", "accountId"];

    public function __construct()
    {
        $this->id = -1;
        $this->firstName = "firstName";
        $this->lastname = "lastname";
        $this->locationId = -1;
        $this->phoneNumber = 0;
        $this->accountId = -1;
    }

    public function constructFull(int $id, string $firstName, int $lastname, int $locationId, int $phoneNumber, int $accountId)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastname = $lastname;
        $this->locationId = $locationId;
        $this->phoneNumber = $phoneNumber;
        $this->accountId = $accountId;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastname" => $this->lastname,
            "locationId" => $this->locationId,
            "phoneNumber" => $this->phoneNumber,
            "accountId" => $this->accountId
        ];
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "firstName"],
            $sqlRes[self::sqlTableName . "lastname"],
            $sqlRes[self::sqlTableName . "locationId"],
            $sqlRes[self::sqlTableName . "phoneNumber"],
            $sqlRes[self::sqlTableName . "accountId"],
        );
    }

  
    public function getId()
    {
        return $this->id;
    }

 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    public function getFirstName()
    {
        return $this->firstName;
    }


    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }


    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLocationId()
    {
        return $this->locationId;
    }
 
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }
}