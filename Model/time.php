<?php


class time
{
    private DateTime $time;

    public function __construct() {
        $this->time = new DateTime();
    }

    public function fromDateTime(DateTime $time){
        $this->time = $time;
        return $this;
    }

    public function fromYMD(string $time){
        $this->time = date_create_from_format("H:i:s", $time);
        return $this;
    }

    public function toString(){
        return $this->time->format("H:i:s");
    }
}