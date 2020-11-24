<?php
session_start();
$PAGE_TITLE = "Create order";
$PAGE_STYLESHEETS = "<link rel='stylesheet' href='../../../css/create_order.css'>";
$PAGE_SCRIPTS = "<script src='../../../js/create_order.js'></script>
                <script src='../../../js/send-order.js'></script>";
require_once "../../../templates/header.php";

if (!isset($_SESSION["account-type"])) {
    header("Location: /cafeteria/login.php");
}


?>
<div class="loading-overlay d-flex justify-content-center align-items-center">
    <i class="fas fa-spinner fa-pulse"></i>
</div>

<div class="image-preview">
    <div class="controls">
        <i class="fas fa-times"></i>
    </div>
    <div class="image-preview-content d-flex justify-content-center align-items-center">
        <img src="" alt="">
    </div>
</div>

<div class="container">
    <div class="row order">
        <h2 class="order-heading col-12 border-bottom mb-5 pb-2">User Order</h2>

        <div class="col-12 col-md-4 menu-text d-flex align-items-center">menu</div>
        <div class="multi-select-dropdown col-12 col-md-4 d-flex flex-column pl-2 pr-2 pl-md-2 pr-md-0 mb-0">
            <div class="select-area border d-flex align-items-center mb-3">
                <span class="dropdown-hint"> Categories:</span>
                <span class="selected-values"> All</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <div class="select-content border">
                <div class="options">
                    <div class="option">
                        <input type="checkbox" checked value="all" id="all-category-select">
                        <label for="all-category-select">All</label>
                    </div>
                    <!-- Here Categories added to dropDown -->
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 search-filter-container pl-2 pr-2 pl-md-2 pr-md-0">
            <div class="search-filter border d-flex align-items-center">
                <input type="text" name="productSearch-input" id="productSearch-input" placeholder="Search" autocomplete="off">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <div class="products-order-container row col-12 px-2 px-md-0 m-0 mt-2">

            <!-- Card -->
            <div class="order-container col-12 col-md-4 p-0 pr-md-2 mb-3">
                <div class="order-wrapper border">
                    <div class="order-items py-3">
                        <p class="hint-for-product-area text-muted text-center m-0">Here is your items per orders</p>
                    </div>
                    <!-- Order notes -->
                    <div class="order-notes row m-0 px-3 display-none">
                        <label for="order-notes" class="col-12 m-0 p-0">Notes</label>
                        <textarea name="order-notes" id="order-notes" rows="4" class="col-12" placeholder="Write your notes here..."></textarea>
                    </div>
                    <!-- Order room -->
                    <div class="order-room px-3 mb-3 display-none">
                        <label for="order-room-select" class="col-2 m-0 mt-3 p-0">Room</label>
                        <select id="order-room-select" class="form-control">
                            <option selected disabled>Select the room</option>
                            <!-- Here rooms added to select -->
                        </select>
                        <div class="alert alert-danger display-none mt-2" id="room-validation" role="alert">
                            Please select the room
                        </div>
                    </div>
                    <div class="w-75 border-bottom mb-3 mx-auto display-none"></div>
                    <div class="total-order-price-container d-flex px-3 align-items-center mb-3 flex-wrap display-none">
                        <span class="col-6">Total:</span>
                        <span class="col-6 font-weight-bolder total-price">0</span>
                        <a href="javascript:;" id="send-order" class="btn btn-success col-12 mt-2">Order now!</a> <!-- will contain data-user-id='1' via JS -->
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="category-product-container col-12 col-md-8 p-0">

                <!-- Here Categories added -->
                <!-- Here after each categories its product added -->
            </div>
        </div>
    </div>
</div>

<?php require_once '../../../templates/footer.php'; ?>