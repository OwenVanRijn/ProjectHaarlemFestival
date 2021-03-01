<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($root . "/Service/sessionService.php");
require_once($root . "/UI/navBarCMSGenerator.php");
require_once($root . "/UI/tableGenerator.php");
require_once ($root . "/Service/jazzactivityService.php");
require_once ($root . "/Service/foodactivityService.php");
require_once ($root . "/Service/danceActivityService.php");

$sessionService = new sessionService();
$user = $sessionService->validateSessionFromCookie();

if (!$user)
    header("Location: login.php");

if (!isset($_GET["event"]))
    header("Location: home.php");

$nav = new navBarCMSGenerator("events.php?event=" . $_GET["event"]);

$nav->assignCss([
        "sel" => "aSel"
]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>CMS - Events</title>
</head>

<script>
    // TODO: put in external script file
    let isBoxOpen = false;

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

    function generateInputField(fieldContent, className, fieldName){
        switch (fieldContent.type){
            case "customListMultiple":
                let select = document.createElement("select");
                select.setAttribute("name", fieldName + "[]");
                select.setAttribute("multiple", '');

                for (const optionIndex in fieldContent.value.options){
                    const option = fieldContent.value.options[optionIndex];
                    const optionIndexNum = Number(optionIndex);
                    const optionElem = document.createElement("option");
                    optionElem.setAttribute("value", optionIndex);
                    optionElem.innerHTML = option;
                    if (fieldContent.value.selected.includes(optionIndexNum))
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
                    optionElem.setAttribute("value", optionIndex);
                    optionElem.innerHTML = option;
                    if (fieldContent.value.selected == optionIndex)
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

        let form = document.createElement("form");
        form.setAttribute("id", "formTop");
        form.setAttribute("action", "../API/activityUpdate.php");
        form.setAttribute("method", "post");

        let formHeader = document.createElement("section");
        let type = document.createElement("h3");
        let exitButton = document.createElement("button");
        type.innerHTML = json.activity.type.value;
        exitButton.innerHTML = "Exit";
        exitButton.onclick = function () {
            document.getElementById("formTop").remove();
            isBoxOpen = false;
        }
        exitButton.setAttribute("form", "");
        formHeader.appendChild(type);
        formHeader.appendChild(exitButton);
        form.appendChild(formHeader);
        document.body.appendChild(form);

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

            form.appendChild(section);
        }

        let send = document.createElement("button");
        send.innerHTML = "Edit";
        form.appendChild(send);
    }

    function openBox(id){
        if (!isBoxOpen){
            httpGetAsync("../API/activityRequest.php?id=" + id, generateHTML)
            isBoxOpen = true;
        }
    }
</script>

<body>
<?php $nav->generate($user) ?>
<section class="main">
    <?php
        $event = $_GET["event"];
        if ($event == "jazz")
            $table = new jazzactivityService();
        elseif ($event == "dance")
            $table = new danceActivityService();
        elseif ($event == "food")
            $table = new foodactivityService();
        // TODO: make page to select events

        if (isset($table)){
            $tableGen = new tableGenerator($table);
            $tableGen->generate();
        }
    ?>
</section>
</body>
