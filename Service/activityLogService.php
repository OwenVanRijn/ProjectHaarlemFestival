<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once ("baseService.php");
require_once ($root . "/DAL/activityLogDAO.php");

class activityLogService extends baseService
{
    public function __construct(){
        $this->db = new activityLogDAO();
    }

    public function insert(activityLog $log){
        return $this->db->insert($log->sqlGetFields());
    }

    public function get(int $limit){
        return $this->db->getArray([
            "order" => "id DESC",
            "limit" => $limit
        ]);
    }
}