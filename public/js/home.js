document.querySelector("#search-form").addEventListener("submit", function(e){
    if (document.querySelector("#search-by").value === ""){
        document.querySelector("#search-by").value = "name";
    }
});

// Add datalist to search form
document.querySelector("#search-by").addEventListener("change", function(e){
    if (document.querySelector("#search-by").value === "status"){
        document.querySelector("#search").setAttribute('list', 'statusList');
    } else {
        document.querySelector("#search").removeAttribute('list');
    }
});

// check a number is entered
document.querySelector("#search").addEventListener("change", function(e){
    if (document.querySelector("#search-by").value === "price up to"){
        if (!isNaN(document.querySelector("#search").value)){
            document.querySelector("#search").classList.remove("border-danger");
            document.querySelector("#search-button").classList.remove("button-disabled");
            document.querySelector("#search-button").disabled = false; 
        } else {
            document.querySelector("#search").classList.add("border-danger");
            document.querySelector("#search-button").classList.add("button-disabled");
            document.querySelector("#search-button").disabled = true; 
        }
    }
});