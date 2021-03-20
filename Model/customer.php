<?php

require_once ("sqlModel.php");
require_once ("account.php");
require_once ("customerLocation.php");

class customer extends sqlModel
{
    private int $id;
    private string $firstName;
    private string $lastname;
    private customerLocation $customerlocation;
    private int $phoneNumber;
    private account $account;

    protected const sqlTableName = "customer";
    protected const sqlFields = ["id", "firstName", "lastname", "phoneNumber", "accountId", "locationId"];
    protected const sqlLinks = ["locationId" => customerLocation::class, "accountId" => account::class];

    public function __construct()
    {
        $this->id = -1;
        //$this->firstName = "firstName";
        //$this->lastname = "lastname";
        //$this->location = null;
        //$this->phoneNumber = 0;
    }

    public function constructFull(int $id, string $firstName, string $lastname, customerLocation $customerlocation, int $phoneNumber, account $account)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastname = $lastname;
        $this->customerlocation = $customerlocation;
        $this->phoneNumber = $phoneNumber;
        $this->account = $account;
        return $this;
    }

    public function sqlGetFields()
    {
        $array = [
            "id" => $this->id
        ];

        if (isset($this->firstName))
            $array["firstName"] = $this->firstName;

        if (isset($this->lastname))
            $array["lastName"] = $this->lastname;

        if (isset($this->customerlocation))
            $array["locationId"] = $this->customerlocation->getId();

        if (isset($this->phoneNumber))
            $array["phoneNumber"] = $this->phoneNumber;

        if (isset($this->account))
            $array["accountId"] = $this->account->getId();

        return $array;
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "firstName"],
            $sqlRes[self::sqlTableName . "lastname"],
            customerLocation::sqlParse($sqlRes),
            $sqlRes[self::sqlTableName . "phoneNumber"],
            account::sqlParse($sqlRes),
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

    public function getLocation()
    {
        return $this->customerlocation;
    }

    public function setLocation($location)
    {
        $this->location = $location;

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

    /**
     * @return account
     */
    public function getAccount(): account
    {
        return $this->account;
    }
}