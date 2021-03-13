<?php


$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/customerLocation.php");
require_once("dynamicQueryGen.php");

class customerLocationDAO extends dynamicQueryGen
{
    public function __construct()
    {
        parent::__construct(customerLocation::class);
    }

    /**
     * @param array $filter
     * @return location[]|location|null
     */
    public function get(array $filter = [])
    {
        return parent::get($filter);
    }
}