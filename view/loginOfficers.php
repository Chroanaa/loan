<?php
$errors = include '../controller/loginOfficersController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officers Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="officer-page d-flex justify-content-center align-items-center" 
     style="min-height: 100vh; background: url(../wwwroot/img/company-bg.jpg)no-repeat center / cover;">

    <div class="container p-4 rounded-3 text-light row">
        <div class="col-sm-12 col-md-6 ">
        <h2 class="mb-5">Officers Login</h2>
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
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
        </div>

        <div class="col-sm-12 col-md-6 mt-3">
            <img src="../wwwroot/img/hero-officers-bg.jpg" class="img-fluid" alt="">
        </div>
    </div>
    
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>