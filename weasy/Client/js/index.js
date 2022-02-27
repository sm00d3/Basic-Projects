"use strict";

let btn = document.getElementById("AddBtn");
let wordInput = document.getElementById("WordsInput");
let WordsLabelError = document.getElementById("WordsLabelError");
let tableBody = document.querySelector("table>tbody");

let Api_URL = `${window.origin}/api/words`;

function genRequest() {
    return new XMLHttpRequest();

}

function ValidateWord(word) {
    
    if( word == null || word == undefined || word == "") {
        WordsLabelError.innerText = "Word Connot be enpty!";
        InjectAlert("Word Connot be enpty!");
        return false;
    } else {
        WordsLabelError.innerText = "";
    }
    return true;
}

function InjectAlert(Message, sucess) {
    let alertHtml = "";
    if(sucess == true) {
        alertHtml = `<div class='alert alert-success col-5 text-center' role='alert'>${Message}</div>`;
    }
    else {
        alertHtml = `<div class='alert alert-danger col-5 text-center' role='alert'>${Message}</div>`;
    }

    document.getElementById("alert").innerHTML = alertHtml;
    setTimeout(() => document.getElementById("alert").innerHTML = "", 2000);
}

function populateTable (data) {
    // Clean Table
    while(tableBody.firstChild) {
        tableBody.removeChild(tableBody.firstChild);
    }
    // Fill Table
    data.forEach(el => {
        const tr = document.createElement("tr");
        Array.from(Object.entries(el)).forEach((cel) => {
            let td = document.createElement("td");
            if(cel[0] == "Id") {
                let btn = `<button type="button" onclick="DeleteEntry(${cel[1]})" class="btn btn-link"><i class="fa-solid fa-trash-can" style="color:red;"></i></button>`;
                td.innerHTML = btn;
            }else{
                td.textContent = cel[1];
            }
            tr.appendChild(td);
        });
        tableBody.appendChild(tr);
    });

}

function ajaxGetCall(url) {
    var request = genRequest();
    request.open("GET", url, true);
    request.onload = function () {
       try {
            let data = JSON.parse(request.responseText);
            if(data?.responseObject?.HasError == true) 
            {
                InjectAlert(data?.ResponseErrorMessage, false);
            } else { 
                populateTable(data.responseObject);
            }
       } catch (e) {
            InjectAlert("An error has occurred", false);
       }
    }

    request.send();
}

function ajaxPostDeleteCall(type, url, Data = null) {
    var request = genRequest();
    request.open(type, url, true);
    if(type == "POST" && Data != null) {
        request.setRequestHeader("Content-Type","text/plain");
    }
    request.onload = function () {
        try {
            let data = JSON.parse(request.responseText);
            if(data?.responseObject?.HasError == true) 
            {
                InjectAlert(data?.ResponseErrorMessage, false);
            } else if ((type == "POST" || type == "DELETE") && data?.responseObject?.HasError == false) { 
                InjectAlert(data?.responseObject?.Message, true);
            }
       } catch (e) {
            InjectAlert("An error has occurred", false);
       }
    }

    if(type == "POST") {
        request.send(Data);
    } else{
        request.send()
    }
}

function loadToTable() {
    ajaxGetCall(Api_URL);
}

function DeleteEntry(id) {
    if(id == null && id !== undefined && id > 0) {
        ajaxPostDeleteCall('DELETE',`${Api_URL}/${id}`);
        loadToTable();
    } else {
        InjectAlert("Cannot delete this word.", false)  
    }
}


document.addEventListener("DOMContentLoaded", () => {
    loadToTable();
});

btn.addEventListener("click", 
    function() {
        const word = wordInput.value;
        if(ValidateWord(word)) {
            ajaxPostDeleteCall("POST", Api_URL, word);
            loadToTable();
        }
    }, true);
