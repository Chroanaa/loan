<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: ../view/login.php");
    exit();
}

if (!isset($_GET['loan_id'])) {
    header("Location: ../view/clientDashboard.php");
    exit();
}

// Connect to the database
require "../model/db.php";

$loan_id = $_GET['loan_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM loan_applications WHERE loan_id = ? AND client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $loan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();

$stmt->close();

if (!$loan || $loan['status'] != 'Approved') {
    header("Location: ../view/clientDashboard.php");
    exit();
}

$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_amount = $_POST['payment_amount'];

    if (empty($payment_amount)) {
        $errors[] = "Payment amount is required.";
    }

    if (empty($errors)) {
        // Calculate remaining balance
        $remaining_balance = $loan['loan_amount'] - $payment_amount;

        // Connect to the database
        require_once "../model/db.php";

        // Insert payment into the database
        $sql = "INSERT INTO loan_repayments (loan_id, payment_amount, remaining_balance) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idd", $loan_id, $payment_amount, $remaining_balance);

        if ($stmt->execute()) {
            // Update loan status if fully paid
            if ($remaining_balance <= 0) {
                $sql = "UPDATE loan_applications SET status = 'Paid' WHERE loan_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $loan_id);
                $stmt->execute();
            }

            $successMessage = "Payment made successfully.";
        } else {
            $errors[] = "Failed to make payment.";
        }


    }
}
?>