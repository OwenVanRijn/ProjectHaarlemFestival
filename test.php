<?php
    require_once ("Service/jazzactivityService.php");
    require_once("UI/tableGenerator.php");

    $jazz = new jazzactivityService();

    //print_r($jazz->getContent());

    $table = new tableGenerator($jazz);
    $table->generate();