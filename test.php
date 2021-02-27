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
                switch (jsonText[key][value].type){
                    case "customListMultiple":
                        var thing = document.createElement("select");
                        thing.setAttribute("name", value);
                        thing.setAttribute("multiple", "");
                        for (var iter in jsonText[key][value].value.options){
                            var option = document.createElement("option");
                            const type = jsonText[key][value].value.options[iter];
                            option.setAttribute("value", type);
                            option.innerHTML = type;
                            if (jsonText[key][value].value.selected.includes(type))
                                option.setAttribute("selected", "");
                            thing.appendChild(option);
                        }
                        document.body.appendChild(thing);
                        break;
                    default:
                        var thing = document.createElement("input");
                        thing.setAttribute("type", jsonText[key][value].type);
                        thing.setAttribute("value", jsonText[key][value].value);
                        thing.setAttribute("name", value);
                        document.body.appendChild(thing);
                        break;
                }
            }
        }
    }

    function generateInputField(fieldContent, className, fieldName){
        switch (fieldContent.type){
            case "customListMultiple":
                let select = document.createElement("select");
                select.setAttribute("name", fieldName);
                select.setAttribute("multiple", '');

                for (const optionIndex in fieldContent.value.options){
                    const option = fieldContent.value.options[optionIndex];
                    const optionElem = document.createElement("option");
                    optionElem.setAttribute("value", option);
                    optionElem.innerHTML = option;
                    if (fieldContent.value.selected.includes(option))
                        optionElem.setAttribute("selected", "");

                    select.appendChild(optionElem);
                }

                return select;

            case "customList":
                let selectSingle = document.createElement("select");
                selectSingle.setAttribute("name", fieldName);

                for (const optionIndex in fieldContent.value.options){
                    const option = fieldContent.value.options[optionIndex];
                    const optionElem = document.createElement("option");
                    optionElem.setAttribute("value", option);
                    optionElem.innerHTML = option;
                    if (fieldContent.value.selected === option)
                        optionElem.setAttribute("selected", "");

                    selectSingle.appendChild(optionElem);
                }

                return selectSingle;

            default:
                let input = document.createElement("input");
                input.setAttribute("type", fieldContent.type);
                input.setAttribute("value", fieldContent.value);
                input.setAttribute("name", fieldName);
                input.setAttribute("id", fieldName);

                let label = input;

                if (fieldContent.type !== "hidden"){
                    label = document.createElement("label");
                    label.setAttribute("for", fieldName);
                    label.innerHTML = fieldName;
                    label.appendChild(input);
                }


                return label;
        }
    }

    function generateHTML(json){
        console.log(json);
        for (const className in json){
            let section = document.createElement("section");
            let sectionHeader = document.createElement("section");
            let sectionContent = document.createElement("section");
            sectionContent.setAttribute("id", className + "Section");
            let header = document.createElement("h3");
            header.innerHTML = className;
            sectionHeader.appendChild(header);
            section.appendChild(sectionHeader);
            section.appendChild(sectionContent);

            for (const fieldName in json[className]){
                const fieldContents = json[className][fieldName];

                if (className === fieldName){
                    let field = generateInputField(fieldContents, className, fieldName);
                    field.addEventListener('change', () => {
                        document.getElementById(className + "Section").remove(); // TODO: gives a typeError if element is already deleted
                    })
                    sectionHeader.appendChild(field)
                    continue;
                }


                sectionContent.appendChild(generateInputField(fieldContents, className, fieldName));
            }

            document.body.appendChild(section);
        }
    }

    httpGetAsync("API/activityRequest.php?id=2", generateHTML)
</script>
