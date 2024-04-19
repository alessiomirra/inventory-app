const form = document.querySelector("#cart-form");

let priceString = (string) => {
    /**
    * Entered value is taken from:  row.querySelector("td:nth-child(row-number)")
    * It's a string like this: "€ 450.4"
    */

    let cleaned = string.innerText.replace("€", "");
    return parseFloat(cleaned);
};

function checkout(products){
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "/checkout", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200){
            window.location = "/checkout";
        } else {
            console.log("Error");
        }
    }; 

    xhr.send(JSON.stringify(products));
};

form.addEventListener("submit", function(e){
    e.preventDefault();
    
    let products = [];
    let productRows = document.querySelectorAll("#cart-form tbody tr");

    productRows.forEach(function(row){
        let product = {
            name: row.querySelector("td:nth-child(1) a").innerText,
            brand: row.querySelector("td:nth-child(2)").innerText,
            price: priceString(row.querySelector("td:nth-child(3")),
            amount: Number(row.querySelector("#amount").value)
        };
        products.push(product);
    });

    checkout(products);
});