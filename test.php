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

    //$table = new tableGenerator($jazz);
    //$table = new tableGenerator($food);
    $table = new tableGenerator($dance);
    //$table->generate();

    $test = new activityDAO();

    //print_r($typeService->getRestaurantTypes(2));

    //$aoa = new artistOnActivityDAO();
    //print_r($aoa->get([
    //    "danceartist.name" => new dbContains("Nicky")
    //]));

    //$nav = new navBarCMSGenerator();
    //$nav->generate();

    $account = new account();
    $account->setPassword("username");
    $account->setRole(0);
    $account->setStatus(0);
    $account->setIsScheduleManager(true);

    $acc = new jazzactivityDAO();
    //$acc->insert($account->sqlGetFields());

    //print_r($acc->get([
    //    "id" => [1,2]
    //]));

    $activityDAO = new activityDAO();
    //print_r($activityDAO->get(["id" => 1]));
    //print_r($dance->getFromActivityIds([1]));


    // @ sander
    $ids = [1,93,117];
    $activities = array_merge($jazz->getFromActivityIds($ids), $food->getFromActivityIds($ids), $dance->getFromActivityIds($ids));
    //var_dump($activities);

    $a = $activityDAO->get([
        "location.id" => 1
    ]);

    //print_r($a[0]->getLocation()->getName());

    //$danceThing = new artistOnActivityDAO();
    //print_r($danceThing->get([
    //    "danceartist.name" => new dbContains("Afro")
    //]));

    $activityDAO->get([
        "id" => 1, // Filter on an activity id
        "type" => ["food", "dance"], // Filter on food or dance activities
        "order" => "id" // Order on the id
    ]);

    ?>

<script>
    function httpGetAsync(theUrl, callback)
    {
        const xmlHttp = new XMLHttpRequest();
        xmlHttp.responseType = 'json';
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.response);
        }
        xmlHttp.open("GET", theUrl, true); // true for asynchronous
        xmlHttp.send(null);
    }

    function generateThing(jsonText){
        console.log(jsonText)
        for (var key in jsonText){
            for (var value in jsonText[key]){
                var thing = document.createElement("input");
                thing.setAttribute("type", jsonText[key][value].type);
                thing.setAttribute("value", jsonText[key][value].value);
                thing.setAttribute("name", value);
                document.body.appendChild(thing);
            }
        }
    }

    httpGetAsync("http://localhost:63342/ProjectHaarlemFestival/hf/API/activityRequest.php?id=2", generateThing)
</script>
