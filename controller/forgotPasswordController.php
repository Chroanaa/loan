<?php
// Include session start if necessary
$errors = [];
$successMessage = '';

// Include PHPMailer autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } else {
        // Check if the email exists in your database
        include '../model/db.php';  // Include your database connection
        $sql = "SELECT * FROM user_tbl WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result->num_rows > 0) {
                // Generate a password reset token (a unique random string)
                $token = bin2hex(random_bytes(50)); // 50 bytes = 100 characters
                $expires = date("U") + 3600;  // Token expiry time (1 hour)

                // Save the token and expiry in the database
                $sql = "INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires);
                    mysqli_stmt_execute($stmt);

                    // Send the reset email using PHPMailer
                    $resetLink = "http://localhost/LoaningPHP/view/resetPassword.php?token=" . $token;
                    $subject = "Password Reset Request";
                    $message = "You requested a password reset. Click the link below to reset your password:\n\n" . $resetLink;

                    try {
                        $mail = new PHPMailer(true);
                        // Set up SMTP settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // SMTP server (e.g., Gmail)
                        $mail->SMTPAuth = true;
                        $mail->Username = 'charleneferrer525@gmail.com'; // Your SMTP username (Gmail address)
                        $mail->Password = 'zxgq zqsb eaal qxfg'; // Your SMTP password (App Password for Gmail)
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption (STARTTLS)
                        $mail->Port = 587; // Port for STARTTLS (465 for SSL)

                        // Sender and recipient
                        $mail->setFrom('charleneferrer525@gmail.com', 'One Puhunan');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(false);  // Plain text email
                        $mail->Subject = $subject;
                        $mail->Body    = $message;

                        // Send the email
                        if ($mail->send()) {
                            $successMessage = "An email with a password reset link has been sent to your email address.";
                        }
                    } catch (Exception $e) {
                        $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            } else {
                $errors[] = "No account found with that email address.";
            }
        } else {
            $errors[] = "Database query failed.";
        }
    }
}
?>