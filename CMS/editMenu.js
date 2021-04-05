let isBoxOpen = false;

function httpGetAsync(theUrl, callback, postUrl = "../API/activityUpdate.php")
{
    const xmlHttp = new XMLHttpRequest();
    xmlHttp.responseType = 'json';
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.response, postUrl);
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
                label.classList.add("leftStack", "center", "editElemHeader");
                entry.appendChild(label);
            }

            let select = document.createElement("select");
            select.setAttribute("name", fieldName + "[]");
            select.setAttribute("multiple", '');
            select.classList.add("leftStack", "marginRightOptions", "inputBox", "me-20px", "widthInherit");
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

            selectSingle.classList.add("inputBox", "inputBoxDropdown");
            return selectSingle;

        case "customTableView":
            let h2 = document.createElement("h2");
            h2.innerHTML = fieldName;
            h2.classList.add("cmsTableHeader");
            entry.appendChild(h2);

            let table = document.createElement("table");
            table.classList.add("cmsTable");
            let tr = document.createElement("tr");
            tr.classList.add("cmsTableRow");
            table.appendChild(tr);

            fieldContent.value.header.forEach(x => {
                let th = document.createElement("th");
                th.innerHTML = x;
                tr.appendChild(th);
            });

            fieldContent.value.rows.forEach(x => {
                tr = document.createElement("tr");
                tr.classList.add("cmsTableRow");
                table.appendChild(tr);
                x.forEach(y => {
                    let td = document.createElement("td");
                    tr.appendChild(td);
                    td.innerHTML = y;
                })
            })

            entry.appendChild(table);
            entry.classList.remove("displayInlineBlock");
            entry.classList.add("max300px");

            if (fieldContent.value.rows.length <= 0){
                entry.setAttribute("hidden", "");
            }

            return entry;

        case "customImgUpload":
            let label = document.createElement("label");
            label.setAttribute("for", fieldName);
            label.innerHTML = fieldName;
            label.classList.add("leftStack", "center", "editElemHeader");
            entry.appendChild(label);

            let imgInput = document.createElement("input");
            imgInput.setAttribute("name", fieldName);
            imgInput.setAttribute("id", fieldName);
            imgInput.classList.add("leftStack", "marginRightOption", "center");
            imgInput.setAttribute("type", "file");
            imgInput.setAttribute("accept", "image/x-png");
            entry.appendChild(imgInput);

            return entry;

        case "numberStepped":
            stepNum = true;
            fieldContent.type = "number";
        default:
            if (fieldContent.type !== "hidden"){
                let label = document.createElement("label");
                label.setAttribute("for", fieldName);
                label.innerHTML = fieldName;
                label.classList.add("leftStack", "center", "editElemHeader");
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
            else {
                input.setAttribute("value", fieldContent.value);
                input.setAttribute("min", "0");
            }


            input.setAttribute("name", fieldName);
            input.setAttribute("id", fieldName);
            input.classList.add("leftStack", "marginRightOption", "inputBox", "me-20px", "widthInherit");
            input.setAttribute("required", "");
            entry.appendChild(input);

            if (stepNum){
                input.setAttribute("step", "0.01");
            }

            return entry;
    }
}

function generateHTML(json, postUrl){
    console.log(json);
    if (!json || json.length === 0){
        isBoxOpen = false;
        return;
    }

    let form = document.createElement("form");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("id", "formTop");
    form.setAttribute("action", postUrl);
    form.setAttribute("method", "post");

    let formHeader = document.createElement("section");
    let type = document.createElement("h3");
    type.classList.add("marginTopBottom", "displayInlineBlock");
    let exitButton = document.createElement("button");
    exitButton.classList.add("marginTopBottom", "floatRight", "blueButton", "pAll-half", "pSide-3");
    if (postUrl === "../API/activityUpdate.php")
        type.innerHTML = json.activity.type.value + " Event";
    else
        type.innerHTML = "Edit";
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
    send.innerHTML = "Save";
    send.classList.add("marginTopBottom", "greenButton", "pAll-half", "pSide-3");
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
    form.id = "checkboxForm";

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
                newHeader.classList.add("checkboxItem");
                table.childNodes[i].insertBefore(newHeader, table.childNodes[i].firstChild);
            }
            else {
                const newEntry = document.createElement("td");
                const checkBox = document.createElement("input");
                checkBox.setAttribute("type", "checkbox");
                checkBox.setAttribute("value", table.childNodes[i].lastChild.firstChild.getAttribute("aid"));
                checkBox.name = "tableCheck[]";
                newEntry.classList.add("checkboxItem");
                newEntry.appendChild(checkBox);
                table.childNodes[i].insertBefore(newEntry, table.childNodes[i].firstChild);
            }
        }

        console.log(table);
    }
}



function openBox(id){
    if (!isBoxOpen){
        httpGetAsync("../API/activityRequest.php?id=" + id, generateHTML);
        isBoxOpen = true;
    }
}

function openNew(type){
    if (!isBoxOpen){
        httpGetAsync("../API/activityRequest.php?type=" + type, generateHTML);
        isBoxOpen = true;
    }
}

function showTopConfirm(msg = ""){
    document.getElementById("topButtons").hidden = true;
    document.getElementById("confirmTopAction").hidden = false;
    document.getElementById("submitTop").innerHTML = "<i class=\"fas fa-pen-square\"></i> " + msg;
}

function hideTopComfirm(){
    document.getElementById("topButtons").hidden = false;
    document.getElementById("confirmTopAction").hidden = true;
}

function removeCheckBoxes(){
    let form = document.getElementById("checkboxForm");
    if (form){
        form.parentElement.appendChild(form.firstChild);
        form.remove();
        let checkboxes = document.getElementsByClassName("checkboxItem");
        while (checkboxes.length)
            checkboxes[0].remove();
    }
    isBoxOpen = false;
    hideTopComfirm();
}

function openSwap(){
    if (!isBoxOpen){
        showTopConfirm("Swap selected activities");
        addCheckBoxes("../API/activitySwap.php");
        isBoxOpen = true;
    }
}

function openDel(){
    if (!isBoxOpen){
        showTopConfirm("Delete selected activities");
        addCheckBoxes("../API/activityDelete.php");
        isBoxOpen = true;
    }
}

function openUser(id){
    if (!isBoxOpen){
        httpGetAsync("../API/customerRequest.php?id=" + id, generateHTML, "../API/customerUpdate.php");
        isBoxOpen = true;
    }
}

function openDanceArtist(id){
    if (!isBoxOpen){
        httpGetAsync("../API/danceArtistRequest.php?id=" + id, generateHTML, "../API/danceArtistUpdate.php");
        isBoxOpen = true;
    }
}