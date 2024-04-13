document.querySelector("#new-category").addEventListener("submit", function(e){
    if (document.querySelector("#category").value.indexOf(" ") !== -1){
        document.querySelector("#name-error").classList.remove("hidden");
        e.preventDefault(); 
    } else {
        document.querySelector("#name-error").classList.add("hidden");
    };
});