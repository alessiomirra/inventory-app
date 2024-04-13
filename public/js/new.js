const fileForm = document.querySelector("#add-from-file");

document.querySelector("#code").addEventListener("change", function(e){
    if (document.querySelector("#code").value.length < 12 || document.querySelector("#code").value.length > 12){
        document.querySelector("#code-error").classList.remove("hidden");
        document.querySelector("#code").classList.add("border-danger");
        document.querySelector("#submit-button").classList.add("disabled");
    } else {
        document.querySelector("#code-error").classList.add("hidden");
        document.querySelector("#code").classList.remove("border-danger")
        document.querySelector("#submit-button").classList.remove("disabled");;
    }
});

// Show modal form
document.querySelector("#modal-button").addEventListener("click", function(e){
    let modal = new bootstrap.Modal(document.querySelector("#modal-form"));
    modal.show();
});


// File Form Handling
let fileRequest = (data) => {
    let xhr = new XMLHttpRequest();

    document.querySelector("#response-loading").classList.remove("hidden");
    document.querySelector("#request-button").classList.add("disabled");

    xhr.open("POST", "/save-request", true);

    xhr.onreadystatechange = function(){
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                // Success
                let response = JSON.parse(xhr.responseText);
                if (response.success){
                    document.querySelector("#form-success").classList.remove("hidden")
                    document.querySelector("#form-error").classList.add("hidden");
                    document.querySelector("#response-loading").classList.add("hidden");
                    document.querySelector("#request-button").classList.remove("disabled");
                } else {
                    document.querySelector("#form-error").classList.remove("hidden");
                    document.querySelector("#form-success").classList.add("hidden")
                    document.querySelector("#response-loading").classList.add("hidden");
                    document.querySelector("#request-button").classList.remove("disabled");
                }
            } else {
                document.querySelector("#form-error").classList.remove("hidden");
                document.querySelector("#response-loading").classList.add("hidden");
                document.querySelector("#request-button").classList.remove("disabled");
            }
        }
    };

    xhr.send(data);

    document.querySelector("#response-loading").classList.add("hidden");
    document.querySelector("#request-button").classList.remove("disabled");

    return xhr;
};

fileForm.addEventListener("submit", (e) => {
    e.preventDefault();
    let data = new FormData(fileForm);
    let request = fileRequest(data);
    fileForm.reset();
});