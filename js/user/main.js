let errorElement = document.getElementById("error");

var validateDate = function(date1,date2){  

    document.getElementById("error").style.display = "none";  
    if( date1 == ""){
        document.getElementById("error").style.display = "block";   
        errorElement.innerText="Date is required";
        return;
    }

    date1 = new Date(date1);
    date2 = new Date(date2);

    if(date1 > date2){
        document.getElementById("error").style.display = "block";    
        errorElement.textContent="Date from should be before date to";
        let target = document.getElementById("submit-date");
        target.parentNode.insertBefore(errorElement , target);
        return;
    }
}
// add event change on (date from)input to check if (date to)input has value call show order function 
let datefrom =  document.getElementById("date-from");
datefrom.addEventListener("change" , (e)=>{
    if(dateTo.value != ""){
        showOrder();
    }
})

//add event on change (date-to) input
let dateTo = document.getElementById("date-to");
dateTo.addEventListener("change", showOrder);    

//on change function to fetch orders
function showOrder() {   

    let valOfDateTo = dateTo.value;
    let valOfDateFrom = document.getElementById("date-from").value;

    validateDate(valOfDateFrom, valOfDateTo);

    document.getElementById("products").style.display = "none";              
    let xhttp;
    let response;
    xhttp = new XMLHttpRequest();                        //ajax 
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("getData").innerHTML = "";
            response = JSON.parse(this.responseText);
            console.log(response);
            let order_row;
            let status;
            for(let i = 0 ; i< response.orders.length ; i++){
                order_row = document.createElement("tr");
                order_row.classList="user-order";
                order_row.setAttribute("data-id" , response.orders[i].order_id);
                order_row.addEventListener("click" , showProduct);

                let order_date = document.createElement("td");
                order_date.textContent = new Date(response.orders[i].date).toDateString();
                order_row.appendChild(order_date);

                switch(response.orders[i].status) {
                    case "0":
                      status = "processing"
                      break;
                    case "1":
                      status = "done"
                      break;
                    default:
                      status = "out of delivery"
                  }

                let order_status = document.createElement("td");
                order_status.textContent = status;
                order_row.appendChild(order_status);

                let order_amount = document.createElement("td");
                order_amount.setAttribute("data-price" , response.prices[i].price);
                order_amount.setAttribute("data-id" , response.orders[i].order_id);
                order_amount.textContent = (response.prices[i].price)+" LE";
                order_row.appendChild(order_amount);

                let order_cancel = document.createElement("td");
                if(response.orders[i].status == 0){
                    let cancel_button = document.createElement("a");
                    cancel_button.textContent = "cancel";
                    cancel_button.classList = "btn btn-danger d-inline";
                    cancel_button.setAttribute("data-id" , response.orders[i].order_id);
                    cancel_button.addEventListener("click" , deleteOrder);
                    order_cancel.appendChild(cancel_button);
                }
                order_row.appendChild(order_cancel);
                document.getElementById("getData").appendChild(order_row);
            }
            document.getElementById("total-sum").textContent = response.sum;
        }
    };
    xhttp.open("GET", "UserOrder.php?dateFrom=" + valOfDateFrom + "&dateTo=" + valOfDateTo); // do functions in php file &  send variables date to & datafrom to php file with get method
    xhttp.send();
}
//show products
function showProduct(e){
    let parentElement = e.target.parentElement;
    let id = parentElement.getAttribute('data-id');
    let xhttp;
    xhttp = new XMLHttpRequest();                     //start ajax
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('products').innerHTML = "";
            let response = JSON.parse(this.responseText);

            for(let i = 0 ; i< response.length ; i++){

                let product = document.createElement("div");
                product.classList.add("card","d-inline-block","product","mr-2","col-md-2");

                let product_image = document.createElement("img");
                product_image.classList.add("product-image","d-block","rounded","ml-4");
                product_image.setAttribute("src" , "../../images/products/"+response[i].image+"");
                product.appendChild(product_image);

                let product_name = document.createElement("span");
                product_name.classList.add("font-weight-bolder");
                product_name.textContent = response[i].name;
                product.appendChild(product_name);

                let product_quantity = document.createElement("span");
                product_quantity.classList.add("d-block" , "font-weight-bolder");
                product_quantity.textContent = "Quantity: "+response[i].quantity;
                product.appendChild(product_quantity);

                let product_total_price = document.createElement("span");
                product_total_price.classList.add("d-block","font-weight-bolder");
                product_total_price.textContent = "Price: "+response[i].quantity * response[i].price+"LE";
                product.appendChild(product_total_price);

                document.getElementById('products').appendChild(product);
                document.getElementById('products').style.display = "block";
            }
        }
    };
    xhttp.open("GET", "UserOrder.php?id=" + id);
    xhttp.send();
}

function deleteOrder(event){
    event.stopPropagation();
    let totalPriceElement = document.getElementById("total-sum"); //get total price element
    let parentElement = event.target.parentElement.parentElement; //tr parent of clicked button
    let id = parentElement.getAttribute("data-id");               //td with data-id att
    let priceElement = document.querySelector("td[data-id='"+id+"']");
    let minus_price = priceElement.getAttribute("data-price");    //price of this order
    let xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            totalPriceElement.textContent = (totalPriceElement.innerText) - minus_price; //calculate total price
            document.getElementById("products").innerHTML = "";
            document.getElementById("products").style.display = "none";
            parentElement.remove();

        }
};
    xhttp.open("GET", "UserOrder.php?deleteOrderId=" + id);
    xhttp.send();
}
//click on order
let user_order = document.querySelectorAll(".user-order");
for(let i = 0 ; i< user_order.length ; i++){  //add event on cancel
    user_order[i].addEventListener("click" , showProduct);   
}
//click  cancel 
let order_product = document.querySelectorAll(".cancel-button");
for(let i = 0 ; i< order_product.length ; i++){  //add event on cancel
    order_product[i].addEventListener("click" , deleteOrder);   
}
