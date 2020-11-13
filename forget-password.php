<?php

session_start();
require_once "database_connection.php";

if (isset($_POST["email"]) && isset($_POST["password"])) {
    // Logout the current logged in user.
    session_destroy();
    session_start();

    $sql = "SELECT user_id FROM User WHERE email = :email";
    $statement = $conn->prepare($sql);
    $statement->bindValue(":email", $_POST["email"], PDO::PARAM_STR);

    if ($statement) {
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION["error"] = "This email doesn't exist in our database. Please contact support";
            header("Location: /cafeteria/forget-password.php");
            return;
        }
    } else {
        $_SESSION["error"] = "Bad input data";
        header("Location: /cafeteria/forget-password.php");
        return;
    }

    $sql = "UPDATE User SET password = :password WHERE user_id = :user_id";
    $statement = $conn->prepare($sql);
    $statement->bindValue(":password", md5($_POST["password"]), PDO::PARAM_STR);
    $statement->bindValue(":user_id", $row["user_id"], PDO::PARAM_INT);
    $statement->execute();

    $_SESSION["success"] = "Password changed successfully.";
    header("Location: /cafeteria/index.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="css/bootstrap.css">
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
                               placeholder="Password" pattern="(.+){8,}" title="Password must be at least 8 digits." required>
                        <label for="password">Password</label>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Reset password"
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
