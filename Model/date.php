<?php


class date
{
    private DateTime $date;

    public function __construct() {
        $this->date = new DateTime();
    }

    public function fromDateTime(DateTime $date){
        $this->date = $date;
        return $this;
    }

    public function fromYMD(string $date){
        $this->date = date_create_from_format("Y-m-d", $date);
        return $this;
    }

    public function toString(){
        return $this->date->format("Y-m-d");
    }

    public function getDateTime(){
        return $this->date;
    }
}