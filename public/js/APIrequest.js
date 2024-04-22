document.addEventListener("DOMContentLoaded", function(e){
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "http://127.0.0.1:5000/get", true);
    xhr.setRequestHeader("Content-Type", "application/json"); 

    xhr.onreadystatechange = function(){
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                console.log(xhr.response);
            }
        }
    }

    xhr.send(JSON.stringify(products));
});