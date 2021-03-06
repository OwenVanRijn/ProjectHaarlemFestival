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
        let entry = document.createElement("section");
        entry.classList.add("displayInlineBlock");

        let stepNum = false;

        switch (fieldContent.type){
            case "customListMultiple":
                if (fieldContent.type !== "hidden"){
                    let label = document.createElement("label");
                    label.setAttribute("for", fieldName);
                    label.innerHTML = fieldName;
                    label.classList.add("leftStack");
                    entry.appendChild(label);
                }

                let select = document.createElement("select");
                select.setAttribute("name", fieldName + "[]");
                select.setAttribute("multiple", '');
                select.classList.add("leftStack");
                select.setAttribute("required", "");

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

                entry.appendChild(select);

                return entry;

            case "customList":
                let selectSingle = document.createElement("select");
                selectSingle.setAttribute("name", fieldName);

                fieldContent.value.options[-1] = ("-- Add --");

                for (let optionIndex in fieldContent.value.options){
                    const option = fieldContent.value.options[optionIndex];
                    const optionElem = document.createElement("option");

                    optionElem.setAttribute("value", optionIndex);
                    optionElem.innerHTML = option;
                    if (fieldContent.value.selected == optionIndex)
                        optionElem.setAttribute("selected", "");

                    selectSingle.appendChild(optionElem);
                }

                //const optionElem = document.createElement("option");
                //optionElem.setAttribute("value", "-1");
                //optionElem.innerHTML = "-- Add --";
                //selectSingle.appendChild(optionElem);

                return selectSingle;

            case "numberStepped":
                stepNum = true;
                fieldContent.type = "number";
            default:
                if (fieldContent.type !== "hidden"){
                    let label = document.createElement("label");
                    label.setAttribute("for", fieldName);
                    label.innerHTML = fieldName;
                    label.classList.add("leftStack");
                    entry.appendChild(label);
                }

                let input;
                if (fieldContent.type === "customTextArea")
                    input = document.createElement("textarea");
                else
                    input = document.createElement("input");


                input.setAttribute("type", fieldContent.type);

                if (fieldContent.type === "customTextArea")
                    input.innerHTML = fieldContent.value;
                else
                    input.setAttribute("value", fieldContent.value);

                input.setAttribute("name", fieldName);
                input.setAttribute("id", fieldName);
                input.classList.add("leftStack", "marginRightOption");
                input.setAttribute("required", "");
                entry.appendChild(input);

                if (stepNum){
                    input.setAttribute("step", "0.01");
                }

                return entry;
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
        type.classList.add("marginTopBottom", "displayInlineBlock");
        let exitButton = document.createElement("button");
        exitButton.classList.add("marginTopBottom", "floatRight");
        type.innerHTML = json.activity.type.value + " Event";
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
            section.classList.add("clearLeft");
            let header = document.createElement("h3");
            header.classList.add("marginTopBottom", "displayInlineBlock");
            header.innerHTML = className;
            sectionHeader.appendChild(header);
            section.appendChild(sectionHeader);
            section.appendChild(sectionContent);

            if (className === "hidden"){
                section.setAttribute("hidden", "");
            }

            for (const fieldName in json[className]){
                const fieldContents = json[className][fieldName];

                if (className === fieldName){
                    let field = generateInputField(fieldContents, className, fieldName);
                    field.addEventListener('change', () => {
                        let classSection = document.getElementById(className + "Section");

                        if (field.children[field.selectedIndex].hasAttribute("selected")){
                            classSection.classList.remove("hidden");
                            classSection.removeAttribute("hidden");
                        }
                        else if (field.children[field.selectedIndex].attributes.value.value == -1) {
                            classSection.classList.remove("hidden");
                            classSection.removeAttribute("hidden");
                            for (let i = 0; i < classSection.children.length; i++){
                                if (classSection.children[i].children.length > 1){
                                    switch (classSection.children[i].children[1].tagName){
                                        case "INPUT":
                                            classSection.children[i].children[1].setAttribute("value", "");
                                            break;
                                        case "TEXTAREA":
                                            classSection.children[i].children[1].innerHTML = "";
                                            break;
                                    }
                                }
                            }
                        }
                        else {
                            classSection.classList.add("hidden");
                            classSection.setAttribute("hidden", "");
                        }
                    })
                    sectionHeader.appendChild(field);
                    field.classList.add("marginTopBottom", "marginLeftOption");
                    continue;
                }


                sectionContent.appendChild(generateInputField(fieldContents, className, fieldName));
            }

            form.appendChild(section);
        }

        let send = document.createElement("button");
        send.innerHTML = "Edit";
        send.onclick = function() {
            let hidden = document.getElementsByClassName("hidden");
            while (hidden.length){
                hidden[0].remove();
            }
        }
        form.appendChild(send);
    }

    function readGetRequest(elem){
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(elem);
    }

    function addCheckBoxes(postUrl){
        const mainSection = document.getElementsByClassName("main")[0];
        let form = document.createElement("form");
        form.appendChild(mainSection);
        form.setAttribute("method", "post");
        form.setAttribute("action", postUrl);

        const type = document.createElement("input");
        type.setAttribute("type", "hidden");
        type.value = readGetRequest("event");
        type.name = "type";
        form.appendChild(type);

        document.body.appendChild(form);

        const tables = document.getElementsByClassName("cmsTable");
        for (let idx = 0; idx < tables.length; idx++){
            const table = tables[idx].firstChild;

            for (let i = 0; i < table.childNodes.length; i++){
                if (i == 0){
                    const newHeader = document.createElement("th");
                    table.childNodes[i].insertBefore(newHeader, table.childNodes[i].firstChild);
                }
                else {
                    const newEntry = document.createElement("td");
                    const checkBox = document.createElement("input");
                    checkBox.setAttribute("type", "checkbox");
                    checkBox.setAttribute("value", table.childNodes[i].lastChild.firstChild.getAttribute("aid"));
                    checkBox.name = "tableCheck[]";
                    newEntry.appendChild(checkBox);
                    table.childNodes[i].insertBefore(newEntry, table.childNodes[i].firstChild);
                }
            }

            console.log(table);
        }
    }

    function openBox(id){
        if (!isBoxOpen){
            httpGetAsync("../API/activityRequest.php?id=" + id, generateHTML)
            isBoxOpen = true;
        }
    }

    function openNew(type){
        if (!isBoxOpen){
            httpGetAsync("../API/activityRequest.php?type=" + type, generateHTML)
            isBoxOpen = true;
        }
    }

    function openSwap(){
        if (!isBoxOpen){
            addCheckBoxes("../API/activitySwap.php");
            isBoxOpen = true;
        }
    }

    function openDel(){
        if (!isBoxOpen){
            addCheckBoxes("../API/activityDelete.php");
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

        if (isset($_GET["err"])){
            $err = htmlspecialchars($_GET["err"], ENT_QUOTES);
            echo "<p class='err'>$err</p>";
        }

        if (isset($_GET["done"])){ // TODO: We should *really* post this
            $done = htmlspecialchars($_GET["done"], ENT_QUOTES);
            echo "<p class='done'>$done</p>";
        }

        if (isset($table)){
            $tables = $table->getTables($user, [
                "tr" => "cmsTableRow",
                "table" => "cmsTable",
                "h3" => "cmsTableHeader",
                "summary" => "cmsSummary",
                "details" => "cmsDetails",]);

            foreach ($tables as $t){
                $t->display();
            }
        }

    ?>

    <button onclick="openNew('<?php echo ucfirst($_GET["event"]) ?>')" type="button">Hey!</button>
    <button onclick="openSwap()" type="button">What</button>
    <button onclick="openDel()" type="button">Del</button>
    <button>Submit</button>
</section>
</body>
