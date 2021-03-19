<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/customerService.php");

class customerEdit extends editBase
{
    private customerService $cs;

    public function __construct(account $account){
        parent::__construct($account);
        $this->cs = new customerService();
    }

    private $lastAccountIsVolunteer = false;

    protected function getHtmlEditHeader()
    {
        $header = [
            "customer" => [
                "firstName" => htmlTypeEnum::text,
                "lastName" => htmlTypeEnum::text,
                "phoneNumber" => htmlTypeEnum::text,
            ],
            "account" => [
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
            "firstName" => $entry->getFirstName(),
            "lastName" => $entry->getLastName(),
            "phoneNumber" => $entry->getPhoneNumber(),
            "username" => $entry->getAccount()->getUsername(),
            "email" => $entry->getAccount()->getEmail(),
            "status" => $entry->getAccount()->getStatus(),
            "role" => $entry->getAccount()->getRole(),
            "isScheduleManager" => $entry->getAccount()->isScheduleManager(),
            "isTicketManager" => $entry->getAccount()->isTicketManager()
        ];
    }

    public function getHtmlEditContent(int $id)
    {
        $customer = $this->cs->getFromId($id);

        if (is_null($customer))
            throw new appException("Invalid customer");

        return $this->packHtmlEditContent($this->getHtmlEditFields($customer));
    }
}