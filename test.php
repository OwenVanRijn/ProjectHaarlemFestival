<?php
    require_once ("Service/jazzactivityService.php");
    require_once ("Service/foodactivityService.php");
    require_once ("Service/danceActivityService.php");
    require_once("UI/tableGenerator.php");
    require_once("Service/restaurantTypeService.php");

    $jazz = new jazzactivityService();
    $food = new foodactivityService();
    $dance = new danceActivityService();

    //print_r($jazz->getContent());

    $table = new tableGenerator($jazz);
    //$table = new tableGenerator($food);
    //$table = new tableGenerator($dance);
    $table->generate();

    $typeService = new restaurantTypeService();

    //print_r($typeService->getRestaurantTypes(2));

    //$aoa = new artistOnActivityDAO();
    //print_r($aoa->get([
    //    "danceartist.name" => new dbContains("Nicky")
    //]));