<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer (use Composer autoload if installed via Composer)
require '../vendor/autoload.php';

// Start session to store error messages
session_start();

if (isset($_POST["submit"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $address = $_POST["address"];
    $contact = $_POST["contact"];
    $role = 'client'; // Default role to 'client'
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["repeat_password"];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $branch_id = $_POST['branch_id'];
    $errors = array();

    // Validation
    if (empty($name) || empty($address) || empty($contact) || empty($username) || empty($email) || empty($password) || empty($repeatPassword)) {
        array_push($errors, "All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid.");
    }

    if (strlen($contact) > 20) {
        array_push($errors, "Contact number is too long.");
    }

    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long.");
    }

    if ($password != $repeatPassword) {
        array_push($errors, "Passwords do not match.");
    }

    require_once "../model/db.php";
    $sql = "SELECT * FROM user_tbl WHERE email = '$email' OR username = '$username'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Email or Username already exists.");
    }

    // Store errors in session if any
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors; // Save errors to session
        header("Location: registration.php"); // Redirect back to registration page
        exit();
    } else {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION["otp"] = $otp;
        $_SESSION["email"] = $email;
        $_SESSION["user_data"] = [
            "name" => $name,
            "address" => $address,
            "contact" => $contact,
            "role" => $role,
            "username" => $username,
            "email" => $email,
            "passwordHash" => $passwordHash,
            "branch_id" => $branch_id,
        ];

        // Send OTP email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server (e.g., Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'charleneferrer525@gmail.com'; // Your SMTP username (Gmail address)
            $mail->Password = 'limv nhja yuxp bdph'; // Your SMTP password (App Password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption (STARTTLS)
            $mail->Port = 587; // Port for STARTTLS (465 for SSL)

            // Recipients
            $mail->setFrom('charleneferrer525@gmail.com', 'One Puhunan'); // Sender email and name
            $mail->addAddress($email, $name); // Recipient email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "
                <h1>Welcome, $name!</h1>
                <p>Your One-Time Password (OTP) is: <b>$otp</b></p>
                <p>This code is valid for 10 minutes.</p>
            ";

            $mail->send();
            header("Location: verifyOTP.php"); // Redirect to OTP verification page
            exit();
        } catch (Exception $e) {
            $_SESSION['errors'] = ["Error sending OTP email: {$mail->ErrorInfo}"]; // Capture PHPMailer errors
            header("Location: registration.php");
            exit();
        }
    }
}
?>