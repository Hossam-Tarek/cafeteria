<?php

session_start();
require_once "database_connection.php";

if (isset($_SESSION["email"]) && isset($_SESSION["account-type"])) {
    if ($_SESSION["account-type"] === "admin") {
        header("Location: /cafeteria/views/admin/index.php");
        return;
    }
    if ($_SESSION["account-type"] === "user") {
        header("Location: /cafeteria/views/user/index.php");
        return;
    }
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
    // Logout the current logged in user.
    session_destroy();
    session_start();

    if ($_POST["email"] === "admin@cafeteria.com" && $_POST["password"] === "CafeteriaAdmin-2020") {
        $_SESSION["name"] = "Admin";
        $_SESSION["email"] = "admin@cafeteria.com";
        $_SESSION["account-type"] = "admin";
        $_SESSION["success"] = "Logged in successfully";
        header("Location: /cafeteria/views/admin/index.php");
        return;
    }

    $sql = "SELECT user_id, name, email, password FROM User WHERE email = :email";
    $statement = $conn->prepare($sql);
    $statement->bindValue(":email", $_POST["email"], PDO::PARAM_STR);

    if ($statement) {
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION["error"] = "This email doesn't exist in our database. Please contact support";
            header("Location: /cafeteria/login.php");
            return;
        }
    } else {
        $_SESSION["error"] = "Bad input data";
        header("Location: /cafeteria/login.php");
        return;
    }

    if (md5($_POST["password"]) === $row["password"]) {
        $_SESSION["id"] = $row["user_id"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["account-type"] = "user";
        $_SESSION["success"] = "Logged in successfully";
        header("Location: /cafeteria/views/user/user-orders.php");
        return;
    } else {
        $_SESSION["error"] = "Incorrect password";
        header("Location: /cafeteria/login.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/scheme.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-7 col-lg-6 mx-auto">
                <form method="POST" class="form">
                    <div class="logo-container text-center">
                        <a class="logo text-center mb-5" href="index.php" alt="logo">
                            <img class="logo__img mb-2" src="/cafeteria/img/logo.png" alt="logo">
                            <h1 class="logo__text">Cafeteria</h1>
                        </a>
                    </div>

                    <?php
                        if (isset($_SESSION["error"])) {
                            echo "<p class='error'>". $_SESSION["error"] ."</p>";
                            unset($_SESSION["error"]);
                        }
                    ?>

                    <div class="form__group mb-3">
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="Email address" autofocus required>
                        <label for="email">Email address</label>
                    </div>
                    <div class="form__group mb-3">
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="checkbox mb-3">
                        <input type="checkbox" id="remember-me">
                        <label for="remember-me"> Remember me</label>
                        <a href="forget-password.php" class="float-right">Forget password</a>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Sign in"
                           class="btn btn-lg btn-primary btn-block">

                    <p class="mt-3 mb-3 text-muted text-center">© 2020</p>
                </form>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
