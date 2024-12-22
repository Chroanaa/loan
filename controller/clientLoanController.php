<?php
session_start();
$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $loan_amount = $_POST['loan_amount'];
    $loan_term = $_POST['loan_term'];
    $interest_rate = $_POST['interest_rate'];
    $status = 'Pending'; // Default status

    // Validate input
    if (empty($loan_amount) || empty($loan_term) || empty($interest_rate)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Connect to the database
        require_once "../model/db.php";

        // Insert loan application into the database
        $sql = "INSERT INTO loan_applications (client_id, loan_amount, loan_term, interest_rate, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidds", $user_id, $loan_amount, $loan_term, $interest_rate, $status);

        if ($stmt->execute()) {
            $successMessage = "Loan application submitted successfully.";
        } else {
            $errors[] = "Failed to submit loan application.";
        }

        $stmt->close();
        $conn->close();
    }
}

return $errors;
?>