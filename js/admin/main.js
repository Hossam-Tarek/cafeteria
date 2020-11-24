$(document).ready(function () {
    // Delete User Record
    $('.deleteuser').on('click', function () {
        let user_id = $(this).attr('data-user');
        Delete(user_id, '../user/deleteuser.php?id=');
    })
    // Delete Product Record
    $('.deleteproduct').on('click', function () {
        let product_id = $(this).attr('data-product');
        Delete(product_id, '../product/deleteproduct.php?id=');
    })
    // Delete Category Record
    $('.deletecategory').on('click', function () {
        let category_id = $(this).attr('data-category');
        Delete(category_id, '../category/deletecategory.php?id=');
    })
    //Dynamic Ajax Function To Delete =>users , product ,category 
    function Delete(id, url) {
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xml.open('GET', url + id, true);
        xml.send();
    }
    // Stylish When Error Apear
    $('.error').prev().css({
        'border': "1px solid red"
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    $(".card").on("click", function () {
        console.log("card clicked.");
        window.location.href = $(this).attr("data-link");
    });
})

// Check Orders Ajax Functions

//Ajax Function That Check Date And Return Date Within That Date
$("input").on('change', function () {
    let from = $('#from_date').val(),
        to = $('#to_date').val(),
        fromTime, toTime, diffDays;

    if (to) {
        let fromDate = new Date(to);
        toTime = fromDate.getTime();
    }
    if (from) {
        let toDate = new Date(from);
        fromTime = toDate.getTime();
    }
    if (to && from) {
        if(toTime <= fromTime) {
            alert ('The to date must be after from date :(');
            return;
        }
        diffDays = Math.ceil((toTime - fromTime) / (1000 * 60 * 60 * 24)); 
    } else {
        return;
    }
    $.ajax({
        method: 'GET',
        url: 'checkuserdate.php',
        data: {
            'from': from,
            'to': to
        },
        success: function (responseText) {
            document.getElementById('select_user').innerHTML = responseText;
        }
    });

});

//Ajax Function That  Display Users And Total Price For All order For Users
function Display(id) {
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('orders_details').innerHTML = this.responseText;
        }
    }
    xml.open('GET', 'orders.php?id=' + id, true);
    xml.send();
}

//Ajax Function That  Display  User Order Details For Order
function DisplayOrderDetails(id) {
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('details').style.visibility = "visible";
            document.getElementById('order_details').innerHTML = this.responseText;
        }
    }
    xml.open('GET', 'orderdetails.php?id=' + id, true);
    xml.send();
}
//Ajax Function That  Display For Every Order 
function Displaydetails(id) {
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('orders').style.visibility = "visible";
            document.getElementById('orderdetails').innerHTML = this.responseText;
        }
    }
    xml.open('GET', 'everyorderdetails.php?id=' + id, true);
    xml.send();
}
