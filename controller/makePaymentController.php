<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['loan_id']) || !isset($_GET['client_id'])) {
    header("Location: loanOfficersManageClients.php");
    exit();
}

$loan_id = $_GET['loan_id'];
$client_id = $_GET['client_id'];

$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    require_once "../model/db.php";

    $payment_amount = $_POST['payment_amount'];

    if (empty($payment_amount)) {
        $errors[] = "Payment amount is required.";
    }

    if (empty($errors)) {
        // Calculate total payments made so far
        $sql = "SELECT SUM(payment_amount) AS total_paid FROM loan_repayments WHERE loan_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_paid = $result->fetch_assoc()['total_paid'];
        $stmt->close();

        // Calculate remaining balance
        $remaining_balance = $loan['loan_amount'] - ($total_paid + $payment_amount);

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

            // Fetch client ID
            $client_id = $loan['client_id'];

            // Insert notification for payment
            $message = "A payment of $payment_amount has been made for your loan with ID $loan_id.";
            $sql = "INSERT INTO notifications (client_id, notification_type, message) VALUES (?, 'Payment Reminder', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $client_id, $message);
            $stmt->execute();
            $stmt->close();

            $successMessage = "Payment made successfully.";
        } else {
            $errors[] = "Failed to make payment.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>