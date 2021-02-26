<?php

require_once ("sqlModel.php");

class account extends sqlModel
{
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private int $status;
    private int $role;
    private bool $isScheduleManager;
    private bool $isTicketManager;

    protected const sqlTableName = "account";
    protected const sqlFields = ["id", "username", "password", "email", "status", "role", "isschedulemanager", "isticketmanager"];

    public const accountNormal = 0;
    public const accountVolunteer = 1;
    public const accountAdmin = 2;
    public const accountSuperAdmin = 3;
    public const accountScheduleManager = 0x10;
    public const accountTicketManager = 0x20;

    public function __construct()
    {
        $this->id = 0;
        $this->username = "username";
        $this->password = "";
        $this->email = "username@email.com";
        $this->status = -1;
        $this->role = -1;
        $this->isScheduleManager = false;
        $this->isTicketManager = false;
    }

    public function constructFull(int $id, string $username, string $password, string $email, int $status, int $role, bool $isScheduleManager, bool $isTicketManager)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->status = $status;
        $this->role = $role;
        $this->isScheduleManager = $isScheduleManager;
        $this->isTicketManager = $isTicketManager;
        return $this;
    }

    public function sqlGetFields()
    {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "status" => $this->status,
            "role" => $this->role,
            "isschedulemanager" => $this->isScheduleManager,
            "isticketmanager" => $this->isTicketManager
        ];
    }

    public function validateLogin(string $username, string $password): bool {
        if ($username !== $this->username)
            return false;

        return password_verify($password, $this->password);
    }

    public static function sqlParse(array $sqlRes): self
    {
        return (new self())->constructFull(
            $sqlRes[self::sqlTableName . "id"],
            $sqlRes[self::sqlTableName . "username"],
            $sqlRes[self::sqlTableName . "password"],
            $sqlRes[self::sqlTableName . "email"],
            $sqlRes[self::sqlTableName . "status"],
            $sqlRes[self::sqlTableName . "role"],
            $sqlRes[self::sqlTableName . "isschedulemanager"],
            $sqlRes[self::sqlTableName . "isticketmanager"],
        );
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @param int $role
     */
    public function setRole(int $role): void
    {
        $this->role = $role;
    }

    /**
     * @param bool $isScheduleManager
     */
    public function setIsScheduleManager(bool $isScheduleManager): void
    {
        $this->isScheduleManager = $isScheduleManager;
    }

    /**
     * @param bool $isTicketManager
     */
    public function setIsTicketManager(bool $isTicketManager): void
    {
        $this->isTicketManager = $isTicketManager;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function isScheduleManager(): bool
    {
        return $this->isScheduleManager;
    }

    /**
     * @return bool
     */
    public function isTicketManager(): bool
    {
        return $this->isTicketManager;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    public function getCombinedRole(): int
    {
        $role = $this->role;
        if ($this->isScheduleManager)
            $role |= account::accountScheduleManager;
        if ($this->isTicketManager)
            $role |= account::accountScheduleManager;
        return $role;
    }
}