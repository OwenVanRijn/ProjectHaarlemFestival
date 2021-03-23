<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/customerService.php");
require_once ($root . "/Service/accountService.php");

class customerEdit extends editBase implements editUpdate
{
    private customerService $cs;
    private accountService $as;

    public function __construct(account $account){
        parent::__construct($account);
        $this->cs = new customerService();
        $this->as = new accountService();
    }

    private $lastAccountIsVolunteer = false;

    protected function getHtmlEditHeader()
    {
        $header = [
            "customer" => [
                "id" => htmlTypeEnum::hidden,
                "firstName" => htmlTypeEnum::text,
                "lastName" => htmlTypeEnum::text,
            ],
            "account" => [
                "accountId" => htmlTypeEnum::hidden,
                "username" => htmlTypeEnum::text,
                "email" => htmlTypeEnum::text,
                "status" => htmlTypeEnum::number,
                "role" => [htmlTypeEnum::number, account::accountAdmin],
            ]
        ];

        if ($this->lastAccountIsVolunteer){
            $header["account"]["isScheduleManager"] = [htmlTypeEnum::checkBox, account::accountAdmin]; // TODO: add checkbox type!
            $header["account"]["isTicketManager"] = [htmlTypeEnum::checkBox, account::accountAdmin];
            $this->lastAccountIsVolunteer = false;
        }

        return $header;
    }

    protected function getHtmlEditFields($entry)
    {
        if ($entry->getAccount()->getRole() == account::accountVolunteer)
            $this->lastAccountIsVolunteer = true;

        return [
            "id" => $entry->getId(),
            "firstName" => $entry->getFirstName(),
            "lastName" => $entry->getLastName(),
            "username" => $entry->getAccount()->getUsername(),
            "email" => $entry->getAccount()->getEmail(),
            "status" => $entry->getAccount()->getStatus(),
            "role" => $entry->getAccount()->getRole(),
            "isScheduleManager" => $entry->getAccount()->isScheduleManager(),
            "isTicketManager" => $entry->getAccount()->isTicketManager(),
            "accountId" => $entry->getAccount()->getId(),
        ];
    }

    public function getHtmlEditContent(int $id)
    {
        $customer = $this->cs->getFromId($id);

        if (is_null($customer))
            throw new appException("Invalid customer");

        return $this->packHtmlEditContent($this->getHtmlEditFields($customer));
    }

    public function processEditResponse(array $post){
        $post = $this->filterHtmlEditResponse($post);

        if (!array_key_exists("id", $post) || !array_key_exists("accountId", $post))
            throw new appException("Invalid POST");

        $customer = new customer();
        $customer->setId((int)$post["id"]);

        if (array_key_exists("firstName", $post))
            $customer->setFirstName($post["firstName"]);

        if (array_key_exists("lastName", $post))
            $customer->setLastname($post["lastName"]);

        $account = new account();
        $account->setId((int)$post["accountId"]);

        if (array_key_exists("username", $post))
            $account->setUsername($post["username"]);

        if (array_key_exists("email", $post)){
            $account->setEmail($post["email"]);
            $customer->setEmail($post["email"]);
        }

        if (array_key_exists("status", $post))
            $account->setStatus((int)$post["status"]);

        if (array_key_exists("role", $post))
            $account->setRole((int)$post["role"]);

        $this->cs->updateCustomer($customer);
        $this->as->updateAccount($account);
    }
}