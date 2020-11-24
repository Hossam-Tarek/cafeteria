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

define("VALID_EXTENSIONS", ["jpeg", "jpg", "png"]);

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

if (isset($_POST["submit"]) && $_POST["submit"] == "Submit") {
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
    if (!isset($_POST["room-id"]) || $_POST["room-id"] == 0) {
        $_SESSION["error"] = "Room name is required";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }

    // Upload user picture.
    if (!empty($_FILES["picture"]["name"])) {
        $picture = $_FILES["picture"];
        $extension = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, VALID_EXTENSIONS)) {
            $_SESSION["error"] = "The file doesn't have a valid extension.";
            header("Location: /cafeteria/views/admin/user/add-user.php");
            return;
        }

        $dir = "../../../images/avatars/";
        $pictureName = $_POST["email"] . "." . $extension;
        $path = $dir . $pictureName;

        // Check if the picture exists.
        if ($picture["tmp_name"]) {
            if ($picture["size"] == 0 || $picture["size"] > 5000000) {
                $_SESSION["error"] = "Picture is too large choose another one and try again.";
                header("Location: /cafeteria/views/admin/user/add-user.php");
                return;
            }
            move_uploaded_file($picture["tmp_name"], $path);
        } else {
            $pictureName = null;
        }
    }

    $sql = "INSERT INTO User (name, email, password, room_id, extra_info, avatar) VALUES
            (:name, :email, :password, :room_id, :extra_info, :avatar)";

    $stm = $conn->prepare($sql);
    $stm->bindValue(":name", $_POST["username"], PDO::PARAM_STR);
    $stm->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
    $stm->bindValue(":password", md5($_POST["password"]), PDO::PARAM_STR);
    $stm->bindValue(":room_id", $_POST["room-id"], PDO::PARAM_INT);
    $stm->bindValue(":extra_info", $_POST["extra-info"],
        $_POST["extra-info"] ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stm->bindValue(":avatar", $pictureName,
        $pictureName ? PDO::PARAM_STR : PDO::PARAM_NULL);

    var_dump($pictureName);

    $conn->beginTransaction();
    if ($stm->execute()) {
        $conn->commit();
    } else {
        $conn->rollBack();
        $_SESSION["error"] = "Cannot save user data, please contact support.";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    }
    header("Location: /cafeteria/views/admin/user/all_users.php");
    return;
}

$editMode = false;
$pageHeader = "Add user";
$userId = 0;
$username = "";
$email = "";
$password = "";
$roomId = 0;
$extraInfo = "";
$picture = "";
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $PAGE_TITLE="Edit user";
    $pageHeader = "Edit user";
    $editMode = true;

    $sql = "SELECT * FROM User WHERE user_id = :user_id";
    $stm = $conn->prepare($sql);
    $stm->bindValue(":user_id", $_GET["id"], PDO::PARAM_INT);

    $stm->execute();
    if ($user = $stm->fetch(PDO::FETCH_ASSOC)) {
        $userId = $user["user_id"];
        $username = $user["name"];
        $email = $user["email"];
        $password = $user["password"];
        $roomId = $user["room_id"];
        $extraInfo = $user["extra_info"];
        $picture = $user["avatar"];
    } else {
        $_SESSION["error"] = "This user does not exist in the database.";
        $PAGE_TITLE="Add user";
        $pageHeader = "Add user";
        $editMode = false;
    }
}

if (isset($_POST["submit"]) && $_POST["submit"] == "Edit") {
    if (empty($_POST["username"])) {
        $_SESSION["error"] = "Username is required.";
        header("Location: /cafeteria/views/admin/user/add-user.php?id=" . $userId);
        return;
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST["email"]) || !validateEmail($_POST["email"])) {
        $_SESSION["error"] = "Invalid email address.";
        header("Location: /cafeteria/views/admin/user/add-user.php?id=" . $userId);
        return;
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["password"]) || !validatePassword($_POST["password"])) {
        $_SESSION["error"] = "Password must be not less than 8 characters.";
        header("Location: /cafeteria/views/admin/user/add-user.php?id=" . $userId);
        return;
    } else {
        $password = md5($_POST["password"]);
    }

    if (empty($_POST["confirm-password"]) || md5($_POST["confirm-password"]) != $password) {
        $_SESSION["error"] = "Password and confirm password must match.";
        header("Location: /cafeteria/views/admin/user/add-user.php?id=" . $userId);
        return;
    }

    if (empty($_POST["room-id"]) || $_POST["room-id"] == 0) {
        $_SESSION["error"] = "Room name is required";
        header("Location: /cafeteria/views/admin/user/add-user.php");
        return;
    } else {
        $roomId = $_POST["room-id"];
    }

    $extraInfo = $_POST["extra-info"];

    // Upload user picture.
    if (!empty($_FILES["picture"]["name"])) {
        $newPicture = $_FILES["picture"];
        $extension = strtolower(pathinfo($newPicture["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, VALID_EXTENSIONS)) {
            $_SESSION["error"] = "The file doesn't have a valid extension.";
            header("Location: /cafeteria/views/admin/user/add-user.php");
            return;
        }

        $dir = "../../../images/avatars/";
        $pictureName = $_POST["email"] . "." . $extension;
        $path = $dir . $pictureName;

        if ($newPicture["size"] == 0 || $newPicture["size"] > 5000000) {
            $_SESSION["error"] = "Picture is too large choose another one and try again.";
            header("Location: /cafeteria/views/admin/user/add-user.php");
            return;
        }
        if (!empty($picture)) {
            unlink($dir . $picture);
        }
        move_uploaded_file($newPicture["tmp_name"], $path);
        $picture = $pictureName;
    }

    $sql = "UPDATE User SET
            name = :name,
            email = :email,
            password = :password,
            room_id = :room_id,
            extra_info = :extra_info,
            avatar = :avatar
            WHERE user_id = :user_id";

    $stm = $conn->prepare($sql);
    $stm->bindValue(":name", $username, PDO::PARAM_STR);
    $stm->bindValue(":email", $email, PDO::PARAM_STR);
    $stm->bindValue(":password", $password, PDO::PARAM_STR);
    $stm->bindValue(":room_id", $roomId, PDO::PARAM_INT);
    $stm->bindValue(":extra_info", $extraInfo,
        $extraInfo ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stm->bindValue(":avatar", $picture,
        $picture ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stm->bindValue(":user_id", $userId, PDO::PARAM_INT);

    var_dump($stm->execute());
    header("Location: /cafeteria/views/admin/user/all_users.php");
    return;
}
?>

<?php require_once "../../../templates/header.php"?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mx-auto">
            <form method="POST" class="form mb-4" enctype="multipart/form-data">
                <h1 class="form__header text-center mt-3 mb-4"><?= $pageHeader ?></h1>

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
                           id="username" value="<?= htmlentities($username) ?>" required>
                </div>

                <label for="email">Email address</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-envelope input-group-text  p-2 px-3"></i>
                    </div>
                    <input type="email" class="form-control" name="email" placeholder="Email address"
                           id="email" pattern="^[0-9a-zA-Z-_]+(\.?[0-9a-zA-Z-_])+@[a-zA-Z]+(\.[a-zA-Z]+)+$"
                           title="Invalid email address" value="<?= htmlentities($email) ?>" required>
                </div>

                <label for="password">Password</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-key input-group-text p-2 px-3"></i>
                    </div>
                    <input type="password" class="form-control" name="password" placeholder="Password"
                           id="password" pattern="(.+){8,}" title="The password must be at least 8 characters" required>
                </div>

                <label for="confirm-password">Confirm password</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-key input-group-text p-2 px-3"></i>
                    </div>
                    <input type="password" class="form-control" name="confirm-password"
                           id="confirm-password" placeholder="Confirm password"
                           pattern="(.+){8,}" title="The password must be at least 8 characters" required>
                </div>

                <label for="room-name">Room name</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <i class="fas fa-door-open input-group-text p-2 px-3"></i>
                    </div>
                    <select class="custom-select" id="room-name" name="room-id">
                        <option value="0" selected>--Please choose a room name--</option>
                        <?php
                            $sql = "SELECT * FROM Room";
                            $stm = $conn->prepare($sql);
                            $stm->execute();
                            while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
                                $selected = false;
                                if ($roomId == $row["room_id"]) {
                                    $selected = "selected";
                                }
                                echo "<option value='".$row["room_id"]."'$selected>".$row["name"] ."</option>";
                            }
                        ?>
                    </select>
                </div>

                <label for="extra-info">Extra info</label>
                <div class="input-group mb-3">
                    <textarea name="extra-info" id="extra-info" cols="50" rows="5" class="form-control"
                              placeholder="Extra info about the user..."
                              maxlength="256"><?= htmlentities($extraInfo) ?></textarea>
                </div>

                <div class="input-group mb-3">
                    <label for="picture">Picture</label>
                    <input type="file" class="form-control-file" id="picture" name="picture">
                </div>

                <div class="input-group d-flex justify-content-center">
                    <?php if ($editMode) { ?>
                        <input class="btn btn-primary m-2 px-4" type="submit" name="submit" id="edit"
                               value="Edit">
                        <a href="all_users.php">
                            <input class="btn btn-secondary m-2 px-4" type="button" name="submit" id="cancel"
                                   value="Cancel">
                        </a>
                    <?php } else { ?>
                        <input class="btn btn-primary m-2 px-4" type="submit" name="submit" id="submit"
                               value="Submit">
                        <input class="btn btn-secondary m-2 px-4" type="reset" name="reset" id="reset"
                               value="Reset">
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../../templates/footer.php"?>
