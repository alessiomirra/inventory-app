let selected = []; 

let checkboxes = document.querySelectorAll("input[type='checkbox'][name='selected']");

checkboxes.forEach(function(checkbox){
    checkbox.addEventListener("change", function(){
        let productID = this.closest('tr').querySelector('a').getAttribute('href').replace("/", "");
        if (this.checked && !selected.includes(productID)){
            selected.push(productID); 
            this.closest('tr').classList.add('table-primary');
        } else {
            let index = selected.indexOf(productID); 
            if (index !== -1){
                selected.splice(index, 1); 
                this.closest('tr').classList.remove('table-primary');
            };
        }

        if (selected.length){
            document.querySelector("#action-box").classList.remove("hidden");
        } else {
            document.querySelector("#action-box").classList.add("hidden");
        }

    });
});

document.querySelector("#delete-selected-button").addEventListener("click", function(e){
    console.log(selected);
    if (!selected.length){
        document.querySelector("#no-items-error").classList.remove("hidden");
    } else {
        document.querySelector("#no-items-error").classList.add("hidden");
    }

    let xhr = new XMLHttpRequest(); 

    xhr.open("POST", '/delete-selected', true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){location.reload();
            } else {
                document.querySelector("request-error").classList.remove("hidden");
            }
        };
    };

    xhr.send("selected="+encodeURIComponent(JSON.stringify(selected)));
});