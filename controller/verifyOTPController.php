<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST["otp"];
    if ($enteredOtp == $_SESSION["otp"]) {
        // OTP verified successfully
        require_once "../model/db.php";

        $userData = $_SESSION["user_data"]; // Get user data stored in session

        // Insert user data into the database
        $sql = "INSERT INTO user_tbl (name, address, contact, role, username, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);

        if ($preparestmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $userData["name"],
                $userData["address"],
                $userData["contact"],
                $userData["role"],
                $userData["username"],
                $userData["email"],
                $userData["passwordHash"]
            );
            mysqli_stmt_execute($stmt);

            // Clear OTP and user data from the session
            unset($_SESSION["otp"]);
            unset($_SESSION["user_data"]);

            // Redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
        }
    } else {
        // OTP is invalid
        echo "<div class='alert alert-danger'>Invalid OTP. Please try again.</div>";
    }
}
?>