<?php
    require_once ("Service/jazzactivityService.php");
    require_once ("Service/foodactivityService.php");
    require_once ("Service/danceActivityService.php");
    require_once ("DAL/activityDAO.php");
    require_once("UI/tableGenerator.php");
    require_once("Service/restaurantTypeService.php");
    require_once ("UI/navBarCMSGenerator.php");
    require_once ("DAL/accountDAO.php");

    $jazz = new jazzactivityService();
    $food = new foodactivityService();
    $dance = new danceActivityService();

    //print_r($jazz->getContent());

    $table = new tableGenerator($jazz);
    //$table = new tableGenerator($food);
    //$table = new tableGenerator($dance);
    //$table->generate();

    $test = new activityDAO();

    //print_r($typeService->getRestaurantTypes(2));

    //$aoa = new artistOnActivityDAO();
    //print_r($aoa->get([
    //    "danceartist.name" => new dbContains("Nicky")
    //]));

    $nav = new navBarCMSGenerator();
    $nav->generate();

    $account = new account();
    $account->setPassword("username");
    $account->setRole(0);
    $account->setStatus(0);
    $account->setIsScheduleManager(true);

    //$acc = new accountDAO();
    //$acc->insert($account->sqlGetFields());