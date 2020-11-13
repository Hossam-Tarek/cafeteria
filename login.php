<?php



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
                    <div class="form__group mb-3">
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="Email address" autofocus>
                        <label for="email">Email address</label>
                    </div>
                    <div class="form__group mb-3">
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Password">
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
