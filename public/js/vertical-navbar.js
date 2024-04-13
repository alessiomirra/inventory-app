let lists = document.querySelectorAll("#list");

function getCategories(){
    let xhr = new XMLHttpRequest(); 

    xhr.open("GET", "/categories", "true"); 
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 || xhr.readyState === 200){
            let response = JSON.parse(xhr.responseText);
            if (response.length){
                response.forEach(function(item){
                    let newItem = document.createElement("a");
                    newItem.classList.add("nav-link", "text-white");
                    newItem.href = `/categories/${item.name}`;
                    newItem.innerHTML = `<i class="bi bi-dot"></i> ${item.name}`;
                    lists.forEach(function(list) {
                        list.appendChild(newItem.cloneNode(true)); 
                    });
                });
            }
        }
    };

    xhr.onerror = function() {
        console.error("Error in Ajax request");
    };

    xhr.send();
}

document.addEventListener("DOMContentLoaded", getCategories());