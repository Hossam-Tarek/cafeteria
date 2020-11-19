$(window).on('load', function () {
    const totalOrderPriceContainer = $('.total-order-price-container'),
        orderNotesContainer = $('.order-notes'),
        orderRoom = $('.order-room');

    $('#send-order').click(function () {
        let orderObj = {
            "user": $(this).data('user-id'),
            "room": "",
            "comment": "",
            "products": []
        }, roomNumber = $("#order-room-select").val();

        if (roomNumber !== null) {
            $('#room-validation').addClass('display-none')
            const orderNotes = $('#order-notes').val();
            prePareOrder(orderObj, roomNumber, orderNotes); // prepare order content
            sendOrder(JSON.stringify(orderObj));
        } else {
            $('#room-validation').removeClass('display-none')
        }
    });

    function prePareOrder(orderObj, roomNumber, comment) {
        let allProducts = $('.one-product');
        orderObj.room = roomNumber;
        orderObj.comment = comment;

        $.each(allProducts, function (indexInArray, product) {
            product = $(product);
            orderObj.products.push({
                "id": product.data('product-id'),
                "quantity": product.data('product-quantity')
            });
        });
    }

    function sendOrder(orderData) {
        $.ajax({
            type: "post",
            url: "http://localhost/cafeteria/api/order/create.php",
            data: orderData,
            success: function (response) {
                resetInputs();
                const productAreaHint = $('.hint-for-product-area'),
                    orderItemsContainer = $('.order-items'),
                    OrdreSuccessMessage = $(`
                    <div class="alert alert-success text-center mx-2 my-2" role="alert">
                        Your order done :)
                    </div>`);

                orderItemsContainer.append(OrdreSuccessMessage);
                orderItemsContainer.removeClass('py-3');
                setTimeout(() => {
                    OrdreSuccessMessage.fadeOut(function () {
                        orderItemsContainer.addClass('py-3');
                        productAreaHint.removeClass('display-none');

                    });
                }, 1500);
            }
        });

        // if there is no items per order display hint to show the area to show the selected products
        function checkItemsPerOrderForDisplayHint() {

            if ($('.one-product').length) {
                totalOrderPriceContainer.removeClass('display-none').prev().removeClass('display-none');
                orderNotesContainer.removeClass('display-none');
                orderRoom.removeClass('display-none');
            } else {
                totalOrderPriceContainer.addClass('display-none').prev().addClass('display-none');
                orderNotesContainer.addClass('display-none');
                orderRoom.addClass('display-none');
            }
        }

        // Reset data inputs
        function resetInputs() {
            $('.one-product').remove();
            $('#order-notes').val('')
            checkItemsPerOrderForDisplayHint();
            $('#order-room-select option:first-child').prop('selected', true);
            $('.product-card input[type="checkbox"]').prop('checked', false);
        }

    }

});
