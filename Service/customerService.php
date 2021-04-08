<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/customerDAO.php");

class customerService extends baseService
{
    public function __construct(){
        $this->db = new customerDAO();
    }

    public function getAll(){
        return $this->db->getArray();
    }

    public function getWithRole(int $role){
        return $this->db->getArray([
            "account.role" => $role
        ]);
    }

    public function getWithRoleOrBelow(int $role){
        $all = $this->getAll();
        $filtered = [];
        foreach ($all as $a){
            if (!$a->hasAccount())
                $filtered[] = $a;

            else if ($a->getAccount()->getRole() <= $role)
                $filtered[] = $a;
        }

        return $filtered;
    }

    public function getFromId(int $customerId){
        return $this->db->get(["id" => $customerId]);
    }

    public function getFromEmail(string $email){
        return $this->db->get(["email" => $email]);
    }

    public function updateCustomer(customer $customer){
        if ($customer->getId() <= 0)
            throw new appException("Id can't be <= 0!");

        return $this->db->update($customer->sqlGetFields());
    }

    public function addCustomer(string $firstname, string $lastname, string $email){
        return $this->db->insert([
            "firstName" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "locationId" => 1
        ]);
    }

    public function getEmails(){
        $emailArray = [];

        $returnArray = $this->db->getDistinctEmail();


    }
}