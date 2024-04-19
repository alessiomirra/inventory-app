let selectField = document.querySelector("#search-by");
let inputField = document.querySelector("#search");

selectField.addEventListener("change", function(){
    if (selectField.value !== "status" && selectField.value !== "price up to" && selectField.value !== "code"){
        inputField.setAttribute("list", "suggestionsList");
    };
});

// Suggestions Requests 

function getSuggestions(data){
    let xhr = new XMLHttpRequest();

    xhr.open("GET", `/suggestions?${data}`, true);

    xhr.onreadystatechange = function(){
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                let response = JSON.parse(xhr.response);
                updateSuggestions(response.data);
            }
        }
    };

    xhr.send(data);
}

function updateSuggestions(response){
    let datalist = document.querySelector("#suggestionsList");
    datalist.innerHTML = "";

    if (response.length){
        response.forEach(function(option){
            let optionElement = document.createElement("option");
            optionElement.value = option;
            datalist.appendChild(optionElement);
        });
    }
}

let inputCount = 0;

inputField.addEventListener("keyup", function(event){
    if (event.key && event.key.length === 1){
        inputCount++;
    };

    if (inputCount >= 3 && event.key && event.key.length === 1){

        if (selectField.value !== "status" && selectField.value !== "price up to" && selectField.value !== "code"){
            if (inputField.value.length >= 3){
                data = `by=${selectField.value === "" ? "name" : selectField.value}&string=${inputField.value}`;
    
                getSuggestions(data);
            }
        }

        inputCount = 0;
    };

});