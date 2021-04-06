<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("editBase.php");
require_once ($root . "/Service/customerService.php");
require_once ($root . "/Service/accountService.php");
require_once ($root . "/Service/activityService.php");
require_once ($root . "/Service/ticketService.php");

class customerEdit extends editBase implements editUpdate
{
    private customerService $cs;
    private accountService $as;
    private ticketService $ts;
    private activityService $acts;

    public function __construct(account $account){
        parent::__construct($account);
        $this->cs = new customerService();
        $this->as = new accountService();
        $this->ts = new ticketService();
        $this->acts = new activityService();
    }

    private $lastAccountIsVolunteer = false;

    protected function getHtmlEditHeader()
    {
        $header = [
            "customer" => [
                "id" => htmlTypeEnum::hidden,
                "firstName" => htmlTypeEnum::text,
                "lastName" => htmlTypeEnum::text,
                "orders" => htmlTypeEnum::tableView,
                "email" => htmlTypeEnum::text,
            ],
        ];

        if ($this->lastHasAcc){
            $header = array_merge($header, [
                "account" => [
                    "accountId" => htmlTypeEnum::hidden,
                    "username" => htmlTypeEnum::text,
                    "accountEmail" => htmlTypeEnum::text,
                    "status" => htmlTypeEnum::number,
                    "role" => [htmlTypeEnum::listInline, account::accountAdmin],
                ]
            ]);
        }

        if ($this->lastAccountIsVolunteer){
            $header["account"]["isScheduleManager"] = [htmlTypeEnum::checkBox, account::accountAdmin]; // TODO: add checkbox type!
            $header["account"]["isTicketManager"] = [htmlTypeEnum::checkBox, account::accountAdmin];
            $this->lastAccountIsVolunteer = false;
        }

        return $header;
    }

    private function findActivity(int $activityId, array $activities) {
        foreach ($activities as $activity){
            if ($activity->getActivity()->getId() == $activityId)
                return $activity;
        }
        return null;
    }

    private bool $lastHasAcc = true;

    protected function getHtmlEditFields($entry)
    {
        $rows = [];
        $activityIds = [];
        $tickets = $this->ts->getTicketsFromCustomer($entry->getId());
        foreach ($tickets as $ticket){
            $activityIds[] = $ticket->getActivity()->getId();
        }

        $activities = $this->acts->getTypedActivityByIds($activityIds);

        foreach ($tickets as $ticket){
            $newRow = [];
            $act = $this->findActivity($ticket->getActivity()->getId(), $activities);
            if (is_null($act)){
                $newRow[] = "(Unknown)";
            }
            else {
                $newRow[] = $act->getName();
            }

            $newRow[] = $ticket->getActivity()->getType();
            $newRow[] = $ticket->getActivity()->getFormattedDateTime();
            $newRow[] = $ticket->getAmount();
            $rows[] = $newRow;
        }

        $array = [
            "id" => $entry->getId(),
            "firstName" => $entry->getFirstName(),
            "lastName" => $entry->getLastName(),
            "email" => $entry->getEmail(),
            "orders" => [
                "header" => ["Title", "Type", "Date", "Amount"],
                "rows" => $rows
            ],
        ];

        $this->lastHasAcc = $entry->hasAccount();

        if ($entry->hasAccount()){
            if ($entry->getAccount()->getRole() == account::accountVolunteer)
                $this->lastAccountIsVolunteer = true;

            $array = array_merge($array, [
                "username" => $entry->getAccount()->getUsername(),
                "accountEmail" => $entry->getAccount()->getEmail(),
                "status" => $entry->getAccount()->getStatus(),
                "role" => [
                    "options" => account::getKeyedRoleInfo($this->account->getRole()),
                    "selected" => [$entry->getAccount()->getRole()]
                ],
                "isScheduleManager" => $entry->getAccount()->isScheduleManager(),
                "isTicketManager" => $entry->getAccount()->isTicketManager(),
                "accountId" => $entry->getAccount()->getId(),
            ]);
        }

        return $array;
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

        if (!array_key_exists("id", $post))
            throw new appException("Invalid POST");

        $customer = new customer();
        $customer->setId((int)$post["id"]);

        if (array_key_exists("firstName", $post))
            $customer->setFirstName($post["firstName"]);

        if (array_key_exists("lastName", $post))
            $customer->setLastname($post["lastName"]);

        if (array_key_exists("email", $post))
            $customer->setEmail($post["email"]);

        $this->cs->updateCustomer($customer);

        $account = new account();
        if (array_key_exists("accountId", $post)){
            $account->setId((int)$post["accountId"]);

            if (array_key_exists("username", $post))
                $account->setUsername($post["username"]);

            if (array_key_exists("accountEmail", $post)){
                $account->setEmail($post["accountEmail"]);
            }

            if (array_key_exists("status", $post))
                $account->setStatus((int)$post["status"]);

            if (array_key_exists("role", $post))
                $account->setRole((int)$post["role"]);

            $this->as->updateAccount($account);
        }
    }
}