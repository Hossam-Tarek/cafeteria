$(window).on('load', function () {
    var allCategories, // Data From server
        allProducts, // Data From server
        allRooms, // Data From server
        allCategoriesName,
        productImageOverlay, // = $('.product-image-overlay a'),
        allSpecifiedCategoriesSelect, // = $('.select-content input:not(#all-category-select)');
        allCategorySelect, // = $('.select-content input#all-category-select');
        allCategoriesheader, // = $('.category');
        productTitles, // = $('.product-title');
        productCardCheckBox; // = $('.product-card input[type="checkbox"]');

    const loadingOverlay = $('.loading-overlay'),
        imagePreview = $('.image-preview'),
        productSearchInput = $('#productSearch-input'),
        orderItems = $('.order-items'),
        totalOrderPrice = $('.total-price'),
        totalOrderPriceContainer = $('.total-order-price-container'),
        orderNotes = $('.order-notes'),
        orderRoom = $('.order-room');

    /* Intialize page with data via APIs */

    // Get Current User
    function getUser() {
        let user;
        $.ajax({
            type: "get",
            url: "http://localhost/cafeteria/api/user/get_user.php",
            dataType: "json",
            success: function (data) {
                user = data;

                if (user.type == 0) { // the user is admin
                    getAllUsers();
                } else {
                    // $('#send-order').data('user-id', user.id);
                    $('#send-order').attr('data-user-id', user.id);
                }
                $('#send-order').attr('data-user-type', user.type);
                // $('#send-order').data('user-type', user.type);
                getAllProducts();
            }
        });
    }

    getUser();

    // Get all users
    function getAllUsers() {
        $.ajax({
            type: "get",
            url: "http://localhost/cafeteria/api/user/read.php",
            dataType: "json",
            success: function (allUsers) {
                let displayAllUsers = `
                    <div class="all-user-select-container col-12 offset-md-4 col-md-8 p-1">
                        <div class="all-user-select-content ml-1">
                            <select class="custom-select user-select">
                                <option selected disabled>Select the customer</option>`;
                // 
                allUsers.forEach(user => {
                    displayAllUsers += `<option value="${user.id}">${user.name}</option>`;
                });
                displayAllUsers += `</select></div></div>`
                displayAllUsers = $(displayAllUsers);
                $('.order-heading').after(displayAllUsers);
                let errorMessage = $(`
                        <div class="col-12 offset-md-4 col-md-8 p-1 display-none" id="user-select-validation">
                            <div class="alert alert-danger ml-1" role="alert">
                                Please select the customer
                            </div>
                        </div>`);
                displayAllUsers.after(errorMessage);

            }
        });
    }

    // Get all products
    function getAllProducts() {
        $.ajax({
            type: "get",
            url: "http://localhost/cafeteria/api/product/read.php",
            dataType: "json",
            success: function (data) {
                allProducts = data;
                getAllCategories();
            }
        });
    }

    // Get all categories
    function getAllCategories() {
        $.ajax({
            type: "get",
            url: "http://localhost/cafeteria/api/category/read.php",
            dataType: "json",
            success: function (data) {
                allCategories = data;
                const categorySelectInput = $('.select-content .options'),
                    categoryProductContainer = $('.category-product-container');
                allCategoriesName = allCategories.map(({ name }) => name);
                $.each(allCategories, function (indexInArray, category) {
                    // Add categories To dropDwon
                    categorySelectInput.append(`
                    <div class="option">
                        <input type="checkbox" value="${category.id}" id="category-${category.id}-${category.name}">
                        <label for="category-${category.id}-${category.name}">${category.name}</label>
                    </div>
                `)
                    // Add Categories to Body
                    categoryProductContainer.append(`
                    <div data-category-id='${category.id}' class="border-bottom py-3 category d-flex justify-content-between align-items-center">
                        <span>${category.name}</span>
                        <i class="fas fa-angle-up border"></i>
                    </div>
                    `);
                    // Add product for each category
                    const productsPerCategory = allProducts.filter(product => product.category_id == category.id),
                        productsContainer = $('<div class="row m-0"></div>');
                    categoryProductContainer.append(productsContainer);

                    $.each(productsPerCategory, function (indexInArray, product) {
                        productsContainer.append(`
                        <div class="col-12 col-md-4 p-0 row m-0 align-items-stretch">
                            <div class="m-2 product-card border">
                                <input type="checkbox" data-product-id='${product.id}'>
                                <div class="product-image">
                                    <div class="product-image-overlay">
                                        <a href="javascript:"><i class="fas fa-search-plus"></i></a>
                                    </div>
                                    <img src="../../../images/products/${product.image}" alt="">
                                </div>
                                <h5 class="product-title">${product.name}</h5>
                                <span class="price">${product.price}</span>
                            </div>
                        </div>                    
                    `);
                    });
                });
                getAllRooms();
            }
        });
    }

    // Get all rooms
    function getAllRooms() {
        $.ajax({
            type: "get",
            url: "http://localhost/cafeteria/api/room/read.php",
            dataType: "json",
            success: function (data) {
                allRooms = data;
                const roomSelectInput = $('#order-room-select');
                $.each(allRooms, function (indexInArray, element) {
                    roomSelectInput.append(`<option value="${element.id}">${element.name}</option>`)
                });
                loadingOverlay.addClass('display-none');
            },
            complete: function () {
                intializeVariables();
            }
        });
    }

    function intializeVariables() {
        productImageOverlay = $('.product-image-overlay a');
        allSpecifiedCategoriesSelect = $('.select-content input:not(#all-category-select)');
        allCategorySelect = $('.select-content input#all-category-select');
        allCategoriesheader = $('.category');
        productTitles = $('.product-title');
        productCardCheckBox = $('.product-card input[type="checkbox"]');
    }

    // change event for customer select
    $(document).on('change', '.user-select', function () {
        let selectedCustomer = $(this).val();
        $('#send-order').attr('data-user-id', selectedCustomer);
        $('#user-select-validation').addClass('display-none');
    });

    $('#order-room-select').click(function () {
        $('#room-validation').addClass('display-none');
    });


    // preview image of product
    $(document).on('click', '.product-image-overlay a', function () {
        imagePreview.fadeIn();
        imagePreview.children('.image-preview-content').children('img').prop('src', $(this).parent().siblings('img').prop('src'));
    });

    // Remove image preview via X
    imagePreview.children('.controls').children('.fa-times').click(function () {
        $(this).parent().parent().fadeOut();
    });

    // Remove image preview via Esc key
    $('body').keyup(function (e) {
        if (e.which == 27) {  // escape key maps to keycode `27` Or e.key === "Escape"
            if (imagePreview.css('display') == 'block') {
                imagePreview.fadeOut();
            }
        }
    });

    // Handle show dropDown for selecting categories on click
    $('.select-area').click(function (e) {
        $(this).siblings().slideToggle(250);
        $(this).children('i').toggleClass('rotate-180');
    });

    // Handle hide dropDown for selecting categories on mouseleave parent
    $('.multi-select-dropdown').mouseleave(function () {
        $(this).children('.select-content').slideUp(250);
        $(this).children('.select-area').children('i').removeClass('rotate');
    });

    /* handle selection in dropDown for selecting categories */

    // all (to show all categories)
    $(document).on('change', '.select-content input#all-category-select', function () {
        $(this).prop("checked", true);
        $('.dropdown-hint').text(""); // remove category-select word text

        // remove all checked catergory other than all
        allSpecifiedCategoriesSelect.prop('checked', false);
        $('.selected-values').text('All');
        allCategoriesheader.removeClass('display-none').next().removeClass('display-none');
    });

    // select one of the categories
    $(document).on('change', '.select-content input:not(#all-category-select)', function () {
        $('.dropdown-hint').text(""); // remove category-select word text
        allCategorySelect.prop('checked', false); // remove all catergory if one or more category is checked
        const allCheckedCategories = getCheckedCategories();

        if (allCheckedCategories.length < 1) {
            allCategorySelect.trigger("change");
        } else {
            let allCheckedCategoriesNames = '';
            $.each(allCheckedCategories, function (indexInArray, valueOfElement) {
                allCheckedCategoriesNames += allCategoriesName[valueOfElement - 1] + ', '; // get categories names
            });
            allCheckedCategoriesNames = allCheckedCategoriesNames.substr(0, allCheckedCategoriesNames.length - 2); // remove last (, )
            $('.selected-values').text(allCheckedCategoriesNames);
            displayCheckedCategoriesOnly(allCheckedCategories);
        }
    });

    // get all selected categories
    function getCheckedCategories() {
        let allCheckedCategories = [];
        $('.select-content input:checked:not(#all-category-select)').each(function (indexInArray, element) {
            allCheckedCategories.push(parseInt($(element).val()));
        });
        return allCheckedCategories;
    }

    // display only selected categories
    function displayCheckedCategoriesOnly(checkedCategories) {
        for (let index = 0; index < allCategoriesheader.length; index++) {
            const category = $(allCategoriesheader[index]);
            if (checkedCategories.indexOf(parseInt(category.attr('data-category-id'))) == -1) {
                category.addClass('display-none').next().addClass('display-none');
            } else {
                category.removeClass('display-none').next().removeClass('display-none');
            }
        }
    }

    // Hide or show products of category (hide content of category only but category head still displayed)
    $(document).on('click', '.category', function () {
        $(this).children('i').toggleClass('rotate180').parent().next().slideToggle();
    });

    // Search in selected product with keyword
    productSearchInput.keyup(function (e) {
        let searchKeyword = $(this).val();
        searchKeyword = '.*' + searchKeyword + '.*'; // intialize keyword to be regular expression

        const regexString = new RegExp(searchKeyword, 'i');
        productTitles.each(function (index, element) {
            const productTitle = $(element),
                productTitleText = productTitle.text();

            if (productTitleText.match(regexString)) {
                $(productTitle).parent('.product-card').parent().fadeIn(0);
            } else {
                $(productTitle).parent('.product-card').parent().fadeOut(0);
            }
        });
    });

    /* Working with order section */
    // Add checked product to order
    $(document).on('change', '.product-card input[type="checkbox"]', function () {
        const productId = $(this).attr('data-product-id'),
            productNameElement = $(this).siblings('h5'),
            productPrice = parseFloat(productNameElement.next().text());

        if ($(this).prop('checked')) {
            addProductToOrder(productNameElement.text(), productPrice, productId);
            calculateTotalPrice(productPrice);
        } else {
            let TotalPricePerProduct = removeProductFromOrder(productId);
            calculateTotalPrice(-TotalPricePerProduct);
        }
    });

    // Add product to order menu
    function addProductToOrder(productName, productPrice, productId) {
        let productInOrder = $(`
        <div class="row align-items-stretch m-0 one-product" data-product-id = '${productId}' data-product-quantity = '1' data-product-price='${productPrice}' style="display:none">
            <span class="col-4 order-product-name d-flex align-items-center justify-content-center overflow-hidden text-break">${productName}</span>
            <span class="col-2 order-product-quantity d-flex align-items-center justify-content-center overflow-hidden">1</span>
            <div class="col-2 order-product-controls d-flex flex-column align-items-center justify-content-center">
                <span class="order-product-plus text-success">+</span>
                <span class="order-product-minus text-danger">-</span>
            </div>
            <span class="col-2 order-product-price d-flex align-items-center justify-content-center overflow-hidden">${productPrice}</span>
            <span class="col-2 order-product-remove d-flex align-items-center justify-content-center overflow-hidden text-danger"><i class="fas fa-times"></i></span>
            <div class="w-75 border-bottom my-3 mx-auto"></div>
        </div>`);
        orderItems.append(productInOrder);
        productInOrder.fadeIn(0);

        // remove product area hint
        $('.hint-for-product-area').addClass('display-none');
        totalOrderPriceContainer.removeClass('display-none').prev().removeClass('display-none');
        orderNotes.removeClass('display-none');
        orderRoom.removeClass('display-none');
    }
    // Remove product from order menu
    function removeProductFromOrder(productId) {
        const productsInOrder = $('.one-product');
        let TotalPricePerProduct = 0;
        for (let index = 0; index < productsInOrder.length; index++) {
            const element = $(productsInOrder[index]);
            if (element.attr('data-product-id') == productId) {
                TotalPricePerProduct = parseFloat(element.attr('data-product-quantity')) * parseFloat(element.attr('data-product-price'));
                element.fadeOut(0, function () {
                    $(this).remove();
                    checkItemsPerOrderForDisplayHint();
                })
                return TotalPricePerProduct;
            }
        }
    }

    // handle plus quantity for product
    $(document).on('click', '.order-product-plus', function () {
        changeQuantity($(this));

        const productPrice = parseFloat($(this).parent().parent().attr('data-product-price'));

        calculateTotalPrice(productPrice);
    });

    // handle minus quantity for product
    $(document).on('click', '.order-product-minus', function () {
        let changeDone = changeQuantity($(this), -1);

        const productPrice = $(this).parent().parent().attr('data-product-price');
        if (changeDone)
            calculateTotalPrice(-productPrice);
    });

    // handle remove for product
    $(document).on('click', '.order-product-remove', function () {
        $(`.product-card input[data-product-id="${$(this).parent().attr('data-product-id')}"]`).prop('checked', false); // Remove check from product of category

        const productTotalAmountPrice = parseFloat($(this).prev().text());

        $(this).parent().fadeOut(function () {
            $(this).remove();
            checkItemsPerOrderForDisplayHint();
            calculateTotalPrice(-productTotalAmountPrice);
        })
    });

    // change quantity function is the take action integer either 1 (default) or -1
    function changeQuantity(element, action = 1) {
        let productQuantityController = element.parent(),
            qunatity = parseInt(productQuantityController.prev().text());

        if (action == -1 && qunatity == 1) {
            alert('Minimum Quantity is one :)\nIf you wanna remove it click on "X" :(');
            return false;
        }

        qunatity += action;
        productQuantityController.prev().text(qunatity);

        // Update data-product-quantity
        element.parent().parent().attr('data-product-quantity', qunatity);

        let productPrice = parseFloat(productQuantityController.parent().attr('data-product-price')),
            totalPricePerProduct = (qunatity * productPrice).toFixed(2);

        parseFloat(productQuantityController.next().text(totalPricePerProduct))
        return true
    }

    // if there is no items per order display hint to show the area to show the selected products
    function checkItemsPerOrderForDisplayHint() {
        let productAreaHint = $('.hint-for-product-area');

        if ($('.one-product').length) {
            productAreaHint.addClass('display-none');
            totalOrderPriceContainer.removeClass('display-none').prev().removeClass('display-none');
            orderNotes.removeClass('display-none');
            orderRoom.removeClass('display-none');
        } else {
            productAreaHint.removeClass('display-none');
            totalOrderPriceContainer.addClass('display-none').prev().addClass('display-none');
            orderNotes.addClass('display-none');
            orderRoom.addClass('display-none');
        }
    }

    function calculateTotalPrice(price) {
        const oldOrderPrice = parseFloat(totalOrderPrice.text()),
            newOrderPrice = (oldOrderPrice + price).toFixed(2);
        totalOrderPrice.text(newOrderPrice); // set new order price
    }

});
