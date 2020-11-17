
var validateDate = function(date1,date2){                  // validate date  
    let errorElement = document.getElementById("error");
    errorElement.innerText="";
    errorElement.classList.remove("alert" , "alert-danger");
    if( date1 == ""){
        errorElement.classList.add("alert" , "alert-danger");
        errorElement.innerText="Date is required";
        return;
    }

    date1 = new Date(date1);
    date2 = new Date(date2);

    if(date1 > date2){
        let errorElement =document.createElement('div');
        errorElement.classList.add("alert","alert-danger");
        errorElement.textContent="Please insert valid data";
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
    var valOfDateTo = dateTo.value;
    var valOfDateFrom = document.getElementById("date-from").value;

    validateDate(valOfDateFrom, valOfDateTo);
    
    document.getElementById("products").style.display = "none";              
    var xhttp;
    xhttp = new XMLHttpRequest();                        //ajax 
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("getData").innerHTML = this.responseText;  // append response data into table 
        }
    };
    xhttp.open("GET", "UserOrder.php?dateFrom=" + valOfDateFrom + "&dateTo=" + valOfDateTo); // do functions in php file &  send variables date to & datafrom to php file with get method
    xhttp.send();
}

 //onclick function to get products related to the clicked order
function getProducts(id) {        
    var xhttp;
    var tr = document.querySelectorAll("[role='button']"); //reset background color of all tr
    for(let i=0 ; i< tr.length ;i++){
        tr[i].style.background="";
    }
    document.querySelector("#id"+id+"").style.background="#c58989"; 
    xhttp = new XMLHttpRequest();                     //start ajax
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("products").style.display = "block";
            document.getElementById("products").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "UserOrder.php?id=" + id);
    xhttp.send();
}

//delete order ajax
function deleteOrder(id) {
    document.getElementById("products").style.display = "none";
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let ele = document.querySelector("#id"+id+"");
            ele.remove();
        }
    };
    xhttp.open("GET", "UserOrder.php?deleteorder=" + id);
    xhttp.send();
}

