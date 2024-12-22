<?php
$errors = include '../controller/loginController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="client-page vh-100 d-flex justify-content-end align-items-center" 
     style="background:url(../wwwroot/img/hero-client-bg.png) no-repeat center /cover;">
        <div class="vh-100 p-3 shadow bg-light d-flex justify-content-center align-items-center" style="width: 500px;"> 
            <div class="container">
                <h2>Sign-in to your account</h2>
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="identifier" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="forgotPassword.php">Forgot Password?</a>

                    </div>
                    <div class="mt-3">
                </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    <label class="mt-4">No account yet? <a href="./registration.php">Create now</a></label>
                </form>
            </div>
        </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>