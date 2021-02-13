<?php

require_once ("Service/cookieManager.php");

class session extends sqlModel
{
    private int $id;
    private string $ipAddress;
    private DateTime $expiryDate;
    private account $account;

    protected const sqlTableName = "session";
    protected const sqlFields = ["id", "ipaddress", "expirydate", "accountid"];
    protected const sqlLinks = ["accountid" => account::class];
    protected const sqlPrimaryIncrement = false;

    public function __construct()
    {
        $this->id = 0;
        $this->ipAddress = "0.0.0.0";
        $this->expiryDate = new DateTime();
        $this->account = new account();
    }

    public function constructFull(int $id, string $ipAddress, DateTime $expiryDate, account $account)
    {
        $this->id = $id;
        $this->ipAddress = $ipAddress;
        $this->expiryDate = $expiryDate;
        $this->account = $account;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "ipaddress" => $this->ipAddress,
            "expirydate" => $this->expiryDate,
            "accountid" => $this->account->getId()
        ];
    }

    public function isOverDate(){
        $date_now = new DateTime();

        return  ($date_now > $this->expiryDate);
    }

    public function setCurrentSession(){
        $cookieManager = new cookieManager("session");
        $cookieManager->set((string)$this->id, 7);
    }

    public function verifySession($sessionId){
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        return ($sessionId == $this->id && $ipAddress == $this->ipAddress && !$this->isOverDate());
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "ipaddress"],
            date_create_from_format("Y-m-d", $sqlRes[self::sqlTableName . "expirydate"]),
            account::sqlParse($sqlRes));
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @param DateTime $expiryDate
     */
    public function setExpiryDate(DateTime $expiryDate): void
    {
        $this->expiryDate = $expiryDate;
    }

    public function setAccount(account $account): void
    {
        $this->account = $account;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return account
     */
    public function getAccount(): account
    {
        return $this->account;
    }
}