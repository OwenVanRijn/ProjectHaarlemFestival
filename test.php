<?php
    require_once ("Service/jazzactivityService.php");
    require_once("UI/tableGenerator.php");
    require_once("Service/restaurantTypeService.php");

    $jazz = new jazzactivityService();

    //print_r($jazz->getContent());

    $table = new tableGenerator($jazz);
    //$table->generate();

    $typeService = new restaurantTypeService();

    print_r($typeService->getRestaurantTypes(2));