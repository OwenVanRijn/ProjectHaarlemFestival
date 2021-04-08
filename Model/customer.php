<?php

require_once ("sqlModel.php");
require_once ("account.php");

class customer extends sqlModel
{
    private int $id;
    private string $firstName;
    private string $lastname;
    private string $email;
    private ?account $account;

    protected const sqlTableName = "customer";
    protected const sqlFields = ["id", "firstName", "lastname", "email", "accountId"];
    protected const sqlLinks = ["accountId" => account::class];

    public function __construct()
    {
        $this->id = -1;
        //$this->firstName = "firstName";
        //$this->lastname = "lastname";
        //$this->location = null;
        //$this->phoneNumber = 0;
    }

    public function constructFull(int $id, string $firstName, string $lastname, string $email, ?account $account)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastname = $lastname;
        $this->email = $email;
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
            $array["lastname"] = $this->lastname;

        if (isset($this->email))
            $array["email"] = $this->email;

        if (isset($this->account))
            $array["accountId"] = $this->account->getId();

        return $array;
    }

    public static function sqlParse(array $sqlRes): self
    {
        $account = null;
        $loc = null;

        if (isset($sqlRes[account::sqlTableName() . "id"]))
            $account = account::sqlParse($sqlRes);

        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "firstName"],
            $sqlRes[self::sqlTableName . "lastname"],
            $sqlRes[self::sqlTableName . "email"],
            $account,
        );
    }

    public function getRoleName() : string
    {
        if (!isset($this->account))
            return "Customer";

        return account::getKeyedRoleInfo()[$this->account->getRole()];
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


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return account
     */
    public function getAccount(): account
    {
        return $this->account;
    }

    public function hasAccount() : bool {
        return isset($this->account);
    }
}