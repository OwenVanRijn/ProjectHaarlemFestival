<?php
ini_set('display_errors', -1);
require_once ("Service/jazzactivityService.php");
require_once ("Service/foodactivityService.php");
require_once ("Service/shoppingcartServiceDB.php");
require_once ("Service/danceActivityService.php");
require_once ("Service/ticketService.php");
require_once ("Service/activityService.php");
require_once ("Service/customerService.php");
require_once ("pdf/emailOrderGen.php");
require_once ("Service/ordersService.php");
require_once ("DAL/activityDAO.php");
require_once("Service/restaurantTypeLinkService.php");
require_once ("UI/navBarCMSGenerator.php");
require_once ("DAL/accountDAO.php");
require_once ("Email/mailer.php");


$pdf = new emailOrderGen();

$pdf->sendEmail("84", "156");

//$ids = [];
//
//$activityService = new activityService();
//
//$danceArray = $activityService->getByType("dance");
//
//$dateArray = $activityService->getByLocation("Club Stalker");
//
//foreach ($dateArray as $activity){
//    $id = $activity->getId();
//
//    $ids[] = $id;
//}
//
//$returnActivites = $activityService->getTypedActivityByIds($ids);
//
//var_dump($returnActivites);



//    $orderQuery = $order->insertOrder(103);
//
//    echo "$orderQuery";

    //$items = $test->getShoppingcartById(16);




//    if(is_object($items)){
//        if (get_class($items) == "activity") {
//            $items = $items;
//        }
//        else {
//            $items = $items->getActivity();
//        }
//
//        echo "{$items->getId()}";
//
//        echo "OBJECT";
//
//        echo "<br>";
//        var_dump($ticket->insertTicket($items->getId(), 101, 50, 1));
//    }
//    else{
//        foreach ($items as $item){
//            //echo "{$item->getId()}";
//
//            echo "ARRAY";
//        }
//    }


//$email->sendMail("louellacreemers@gmail.com", "Test", "This is a test");

    //var_dump($test->getFromId($test->addCustomer("firstname", "lastname", "email")));

//    $jazz = new jazzactivityService();
//    $food = new foodactivityService();
//    $dance = new danceActivityService();
//
//    //print_r($jazz->getContent());
//
//    //$table = new tableGenerator($jazz);
//    //$table = new tableGenerator($food);
//    $table = new tableGenerator($dance);
//    //$table->generate();
//
//    $test = new activityDAO();
//
//    //print_r($typeService->getRestaurantTypes(2));
//
//    //$aoa = new artistOnActivityDAO();
//    //print_r($aoa->get([
//    //    "danceartist.name" => new dbContains("Nicky")
//    //]));
//
//    //$nav = new navBarCMSGenerator();
//    //$nav->generate();
//
//    $account = new account();
//    $account->setPassword("username");
//    $account->setRole(0);
//    $account->setStatus(0);
//    $account->setIsScheduleManager(true);
//
//    $acc = new jazzactivityDAO();
//    //$acc->insert($account->sqlGetFields());
//
//    //print_r($acc->get([
//    //    "id" => [1,2]
//    //]));
//
//    $activityDAO = new activityDAO();
//    //print_r($activityDAO->get(["id" => 1]));
//    //print_r($dance->getFromActivityIds([1]));
//
//
//    // @ sander
//    $ids = [1,93,117];
//    $activities = array_merge($jazz->getFromActivityIds($ids), $food->getFromActivityIds($ids), $dance->getFromActivityIds($ids));
//    //var_dump($activities);
//
//    $a = $activityDAO->get([
//        "location.id" => 1
//    ]);
//
//    //print_r($a[0]->getLocation()->getName());
//
//    //$danceThing = new artistOnActivityDAO();
//    //print_r($danceThing->get([
//    //    "danceartist.name" => new dbContains("Afro")
//    //]));
//
//    $activityDAO->get([
//        "id" => 1, // Filter on an activity id
//        "type" => ["food", "dance"], // Filter on food or dance activities
//        "order" => "id" // Order on the id
//    ]);
//
//    ?>


<!---->
<!--<script>-->
<!--    let isBoxOpen = false;-->
<!---->
<!--    function httpGetAsync(theUrl, callback)-->
<!--    {-->
<!--        const xmlHttp = new XMLHttpRequest();-->
<!--        xmlHttp.responseType = 'json';-->
<!--        xmlHttp.onreadystatechange = function() {-->
<!--            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)-->
<!--                callback(xmlHttp.response);-->
<!--        }-->
<!--        xmlHttp.open("GET", theUrl, true); // true for asynchronous-->
<!--        xmlHttp.send(null);-->
<!--    }-->
<!---->
<!--    function generateInputField(fieldContent, className, fieldName){-->
<!--        switch (fieldContent.type){-->
<!--            case "customListMultiple":-->
<!--                let select = document.createElement("select");-->
<!--                select.setAttribute("name", fieldName);-->
<!--                select.setAttribute("multiple", '');-->
<!---->
<!--                for (const optionIndex in fieldContent.value.options){-->
<!--                    const option = fieldContent.value.options[optionIndex];-->
<!--                    const optionElem = document.createElement("option");-->
<!--                    optionElem.setAttribute("value", option);-->
<!--                    optionElem.innerHTML = option;-->
<!--                    if (fieldContent.value.selected.includes(option))-->
<!--                        optionElem.setAttribute("selected", "");-->
<!---->
<!--                    select.appendChild(optionElem);-->
<!--                }-->
<!---->
<!--                return select;-->
<!---->
<!--            case "customList":-->
<!--                let selectSingle = document.createElement("select");-->
<!--                selectSingle.setAttribute("name", fieldName);-->
<!---->
<!--                for (const optionIndex in fieldContent.value.options){-->
<!--                    const option = fieldContent.value.options[optionIndex];-->
<!--                    const optionElem = document.createElement("option");-->
<!--                    optionElem.setAttribute("value", option);-->
<!--                    optionElem.innerHTML = option;-->
<!--                    if (fieldContent.value.selected === option)-->
<!--                        optionElem.setAttribute("selected", "");-->
<!---->
<!--                    selectSingle.appendChild(optionElem);-->
<!--                }-->
<!---->
<!--                return selectSingle;-->
<!---->
<!--            default:-->
<!--                let input = document.createElement("input");-->
<!--                input.setAttribute("type", fieldContent.type);-->
<!--                input.setAttribute("value", fieldContent.value);-->
<!--                input.setAttribute("name", fieldName);-->
<!--                input.setAttribute("id", fieldName);-->
<!---->
<!--                let label = input;-->
<!---->
<!--                if (fieldContent.type !== "hidden"){-->
<!--                    label = document.createElement("label");-->
<!--                    label.setAttribute("for", fieldName);-->
<!--                    label.innerHTML = fieldName;-->
<!--                    label.appendChild(input);-->
<!--                }-->
<!---->
<!---->
<!--                return label;-->
<!--        }-->
<!--    }-->
<!---->
<!--    function generateHTML(json){-->
<!--        console.log(json);-->
<!---->
<!--        let form = document.createElement("form");-->
<!--        form.setAttribute("id", "formTop");-->
<!---->
<!--        let formHeader = document.createElement("section");-->
<!--        let type = document.createElement("h3");-->
<!--        let exitButton = document.createElement("button");-->
<!--        type.innerHTML = json.activity.type.value;-->
<!--        exitButton.innerHTML = "Exit";-->
<!--        exitButton.onclick = function () {-->
<!--            document.getElementById("formTop").remove();-->
<!--            isBoxOpen = false;-->
<!--        }-->
<!--        formHeader.appendChild(type);-->
<!--        formHeader.appendChild(exitButton);-->
<!--        form.appendChild(formHeader);-->
<!--        document.body.appendChild(form);-->
<!---->
<!--        for (const className in json){-->
<!--            let section = document.createElement("section");-->
<!--            let sectionHeader = document.createElement("section");-->
<!--            let sectionContent = document.createElement("section");-->
<!--            sectionContent.setAttribute("id", className + "Section");-->
<!--            let header = document.createElement("h3");-->
<!--            header.innerHTML = className;-->
<!--            sectionHeader.appendChild(header);-->
<!--            section.appendChild(sectionHeader);-->
<!--            section.appendChild(sectionContent);-->
<!---->
<!--            for (const fieldName in json[className]){-->
<!--                const fieldContents = json[className][fieldName];-->
<!---->
<!--                if (className === fieldName){-->
<!--                    let field = generateInputField(fieldContents, className, fieldName);-->
<!--                    field.addEventListener('change', () => {-->
<!--                        document.getElementById(className + "Section").remove();
<!--                    })-->
<!--                    sectionHeader.appendChild(field)-->
<!--                    continue;-->
<!--                }-->
<!---->
<!---->
<!--                sectionContent.appendChild(generateInputField(fieldContents, className, fieldName));-->
<!--            }-->
<!---->
<!--            form.appendChild(section);-->
<!--        }-->
<!--    }-->
<!---->
<!--    function openBox(id){-->
<!--        if (!isBoxOpen){-->
<!--            httpGetAsync("API/activityRequest.php?id=" + id, generateHTML)-->
<!--            isBoxOpen = true;-->
<!--        }-->
<!--    }-->
<!--</script>-->
