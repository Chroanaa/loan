<?php
    include '../controller/verifyOTPController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <div class="container-fluid d-flex justify-content-center align-items-center" style="background: url(../wwwroot/img/hero-bg.jpg) no-repeat center / cover; min-height: 100vh;" >
        <div class="bg-light shadow-sm p-5 rounded-3 border verify-otp-container" style="min-width: 50%">
            <form method="POST" action="" class="verify-otp-form">
                <h2 class="text-center">OTP Verification</h2>
                <p>
                    We sent a verification code to
                    <?php echo $_SESSION['user_data']['email']; ?>.
                    To verify your email address, please check your inbox and enter the code below.
                </p>
                <input type="text" class="form-control verify-otp-input" id="otp" name="otp" placeholder="Enter 6-digit code" required>
                <button type="submit" class="btn btn-primary mt-3 verify-otp-btn">Verify code</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>