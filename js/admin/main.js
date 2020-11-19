$(document).ready(function(){
    // Delete User Record
    $('.deleteuser').on('click',function(){
        let user_id=$(this).attr('data-user');
        Delete(user_id,'../user/deleteuser.php?id=');
    })
    // Delete Product Record
    $('.deleteproduct').on('click',function(){
        let product_id=$(this).attr('data-product');
        Delete(product_id,'../product/deleteproduct.php?id=');
    })
    // Delete Category Record
    $('.deletecategory').on('click',function(){
        let category_id=$(this).attr('data-category');
        Delete(category_id,'../category/deletecategory.php?id=');
    })
    //Dynamic Ajax Function To Delete =>users , product ,category 
    function Delete(id,url){
        var xml=new XMLHttpRequest();
        xml.onreadystatechange=function(){
            if(this.readyState ==4 && this.status ==200){
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xml.open('GET',url+id,true);
        xml.send();
    }
    // Stylish When Error Apear
    $('.error').prev().css({
        'border':"1px solid red"
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    $(".card").on("click", function() {
        console.log("card clicked.");
        window.location.href = $(this).attr("data-link");
    });
})
