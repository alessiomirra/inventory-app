const form = document.querySelector("#checkout-form"); 
const personRadio = document.querySelector("#person");
const companyRadio = document.querySelector("#company");
const personNameContainer = document.querySelector("#person-name-container");
const companyNameContainer = document.querySelector("#company-name-container");
const vatContainer = document.querySelector("#vat-container");
const shippingContainer = document.querySelector("#shipping-box")
const inStore =  document.querySelector("#in_store");
const sameAdd =  document.querySelector("#same_address");  


// show / hide elements when radio and checks are checked
personRadio.addEventListener("change", function() {
    if (this.checked){
        personNameContainer.classList.remove("hidden");
        companyNameContainer.classList.add("hidden");
        vatContainer.classList.add("hidden");
    }
});

companyRadio.addEventListener("change", function() {
    if (this.checked){
        personNameContainer.classList.add("hidden")
        companyNameContainer.classList.remove("hidden");
        vatContainer.classList.remove("hidden");
    }
});

inStore.addEventListener("change", function(){
    if (this.checked){
        shippingContainer.classList.add("hidden");
    } else {
        shippingContainer.classList.remove("hidden");
    }
});

sameAdd.addEventListener("change", function(){
    if (this.checked){
        shippingContainer.classList.add("hidden");
    } else {
        shippingContainer.classList.remove("hidden");
    }
});

// form validation
checkForm = () => {

    // name check
    if (personRadio.checked){
        if (document.querySelector("#first-name").value === "" || document.querySelector("#last-name").value === ""){
            document.querySelector("#error").classList.remove("hidden");
            document.querySelector("#first-name").classList.add("border-danger");
            document.querySelector("#last-name").classList.add("border-danger");

            return false 
        }
    } else if (companyRadio.checked){
        if (document.querySelector("#name").value === "" || document.querySelector("#vat").value === ""){
            document.querySelector("#error").classList.remove("hidden");
            document.querySelector("#name").classList.add("border-danger");
            document.querySelector("#vat").classList.add("border-danger");

            return false 
        }
    }

    // shipping check 
    if (!document.querySelector("#in_store").checked && !document.querySelector("#same_address").checked ){
        if (document.querySelector("#ship_nation").value === "" || document.querySelector("#ship_city").value === "" || document.querySelector("#ship_zip").value === "" || document.querySelector("#ship_address").value === ""){
            document.querySelector("#ship_nation").classList.add("border-danger");
            document.querySelector("#ship_city").classList.add("border-danger");
            document.querySelector("#ship_zip").classList.add("border-danger");
            document.querySelector("#ship_address").classList.add("border-danger");
            document.querySelector("#shipping-error").classList.remove("hidden");

            return false 
        };
    };
    if (document.querySelector("#same_address").checked){
        if (document.querySelector("#nation").value === "" || document.querySelector("#city").value === "" || document.querySelector("#zip").value === "" || document.querySelector("#address").value === ""){
            document.querySelector("#address-error").classList.remove("hidden");
            document.querySelector("#nation").classList.add("border-danger");
            document.querySelector("#city").classList.add("border-danger");
            document.querySelector("#zip").classList.add("border-danger");
            document.querySelector("#address").classList.add("border-danger");

            return false 
        };
    };

    // payment 
    if (!document.querySelector("#cash").checked && !document.querySelector("#card").checked){
        document.querySelector("#payment-error").classList.remove("hidden"); 

        return false 
    }

    return true; 
};

// form request

let sendRequest = (formData) => {
    let xhr = new XMLHttpRequest();

    document.querySelector("#loading").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");

    xhr.open("POST", "/invoice", true);

    xhr.onreadystatechange = function(){
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                // Success
                document.querySelector("#loading").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");

                // Show modal
                let myModal = new bootstrap.Modal(document.getElementById('success-modal'));
                myModal.show();

            } else {
                // Error
                document.querySelector("generic-error").classList.remove("hidden");
            }
        }
    }

    // Send data 
    xhr.send(formData);
};

// form submitting 
form.addEventListener("submit", (e) => {
    e.preventDefault();
    check = checkForm(); 
    if (check){
        //let formData = getData();
        let formData = new FormData(form);

        let request = sendRequest(formData); 

    } else {
        document.querySelector("#generic-error").classList.remove("hidden");
    }
});