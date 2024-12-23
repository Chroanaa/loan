<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

require_once "../model/db.php";

$loan_id = $_GET['loan_id'];
$client_id = $_GET['client_id'];

// Delete loan application
$sql = "DELETE FROM loan_applications WHERE loan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);

if ($stmt->execute()) {
    // Insert notification for deleted loan
    $message = "Your loan application with ID $loan_id has been deleted.";
    $sql = "INSERT INTO notifications (client_id, notification_type, message) VALUES (?, 'Loan Status Update', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $client_id, $message);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Loan application deleted successfully.";
} else {
    $_SESSION['errors'] = ["Failed to delete loan application."];
}

$conn->close();

header("Location: ../view/loanOfficersManageClientLoans.php?client_id=$client_id");
exit();
?>