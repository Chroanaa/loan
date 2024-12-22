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
    <div class="container mt-5 verify-otp-container">
        <form method="POST" action="" class="verify-otp-form">
            <h2 class="text-center">OTP Verification</h2>
            <img src="../images/otp-verify.png" alt="OTP Verification" />
            <p>
                We sent a verification code to
                <?php echo $_SESSION['user_data']['email']; ?>.
                To verify your email address, please check your inbox and enter the code below.
            </p>
            <input type="text" class="form-control verify-otp-input" id="otp" name="otp" placeholder="Enter 6-digit code" required>
            <button type="submit" class="btn btn-primary verify-otp-btn">Verify code</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>