<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/accountDAO.php");

class accountService extends baseService
{
    public function __construct(){
        $this->db = new accountDAO();
    }

    public function getAll(){
        return $this->db->get();
    }

    public function updateAccount(account $account){
        if ($account->getId() <= 0)
            throw new appException("Id can't be <= 0!");

        return $this->db->update($account->sqlGetFields());
    }
}