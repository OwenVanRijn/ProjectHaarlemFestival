<?php

// Generated by a python script so my intellisense can figure out what to do
// Link to script: https://gist.github.com/OwenVanRijn/461febf6d58596866a9bd42d4c87b4ea
// This is dumb, but i don't see another way to union a return type

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Model/ticket.php");
require_once("dynamicQueryGen.php");

class ticketDAO extends dynamicQueryGen
{
    public function __construct()
    {
        parent::__construct(ticket::class);
    }

    /**
     * @param array $filter
     * @return ticket[]|ticket|null
     */
    public function get(array $filter = [])
    {
        return parent::get($filter);
    }
}