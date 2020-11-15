<?php

session_start();
require_once "../../../database_connection.php";

if (!isset($_SESSION["account-type"]) || $_SESSION["account-type"] !== "admin") {
    header("Location: /cafeteria/index.php");
    return;
}

$PAGE_TITLE="Add User";
$PAGE_STYLESHEETS = "<link rel='stylesheet' href='/cafeteria/css/admin/add-user.css'>";
$PAGE_SCRIPTS = "";

// Validate password must be not less than 8 characters.
function validatePassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    return true;
}

// Validate email address.
function validateEmail($email) {
    $pattern = "/^[0-9a-z-_]+(\.?[0-9a-z-_])+@[a-z]+(\.[a-z]+)+$/i";
    return preg_match($pattern, $email);
}

if (isset($_POST["submit"])) {
    if (!isset($_POST["username"]) || strlen($_POST["username"]) < 1) {
        $_SESSION["error"] = "Username is required.";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
    if (!isset($_POST["email"]) || !validateEmail($_POST["email"])) {
        $_SESSION["error"] = "Invalid email address.";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
    if (!isset($_POST["password"]) || !validatePassword($_POST["password"])) {
        $_SESSION["error"] = "Password must be not less than 8 characters.";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
    if (!isset($_POST["confirm-password"]) || !validatePassword($_POST["confirm-password"])) {
        $_SESSION["error"] = "Password and confirm password must match.";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
    if (!isset($_POST["room-name"]) || $_POST["room-name"] == 0) {
        $_SESSION["error"] = "Room name is required";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
}
?>

<?php require_once "../../../templates/header.php"?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mx-auto">
            <form method="POST" class="form mb-4">
                <h1 class="form__header text-center mt-3 mb-4">Add user</h1>

                <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error-message'>".$_SESSION["error"]."</p>";
                    unset($_SESSION["error"]);
                }
                ?>

                <label for="username">Username</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-user input-group-text  p-2 px-3"></i>
                    </div>
                    <input type="text" class="form-control" name="username" placeholder="username"
                           id="username">
                </div>

                <label for="email">Email address</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-envelope input-group-text  p-2 px-3"></i>
                    </div>
                    <input type="email" class="form-control" name="email" placeholder="Email address"
                           id="email">
                </div>

                <label for="password">Password</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-key input-group-text p-2 px-3"></i>
                    </div>
                    <input type="password" class="form-control" name="password" placeholder="Password"
                           id="password">
                </div>

                <label for="confirm-password">Confirm password</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-key input-group-text p-2 px-3"></i>
                    </div>
                    <input type="password" class="form-control" name="confirm-password"
                           id="confirm-password"
                           placeholder="Confirm password">
                </div>

                <label for="room-name">Room name</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-door-open input-group-text p-2 px-3"></i>
                    </div>
                    <select class="custom-select" id="room-name" name="room-name">
                        <option value="0" selected>--Please choose a room name--</option>
                        <?php
                            $sql = "SELECT * FROM Room";
                            $stm = $conn->prepare($sql);
                            $stm->execute();
                            while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='".$row["room_id"]."'>".$row["name"]."</option>";
                            }
                        ?>
                    </select>
                </div>

                <label for="extra-info">Extra info</label>
                <div class="input-group mb-3">
                    <textarea name="extra-info" id="extra-info" cols="50" rows="5" class="form-control"
                              maxlength="256" placeholder="Extra info about the user..."></textarea>
                </div>

                <div class="input-group mb-3">
                    <label for="picture">Picture</label>
                    <input type="file" class="form-control-file" id="picture" name="picture">
                </div>

                <div class="input-group d-flex justify-content-center">
                    <input class="btn btn-primary m-2 px-4" type="submit" name="submit" id="submit"
                           value="Submit">
                    <input class="btn btn-secondary m-2 px-4" type="reset" name="reset" id="reset"
                           value="Reset">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../../templates/footer.php"?>
