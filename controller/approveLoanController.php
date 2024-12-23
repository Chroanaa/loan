<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: ../view/login.php");
    exit();
}

if (!isset($_GET['loan_id']) || !isset($_GET['client_id'])) {
    header("Location: ../view/loanOfficersManageClients.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$loan_id = $_GET['loan_id'];
$client_id = $_GET['client_id'];

// Update loan status to 'Approved'
$sql = "UPDATE loan_applications SET status = 'Approved' WHERE loan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);

if ($stmt->execute()) {
    // Insert notification for approved loan
    $message = "Your loan application with ID $loan_id has been approved.";
    $sql = "INSERT INTO notifications (client_id, notification_type, message) VALUES (?, 'Loan Status Update', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $client_id, $message);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Loan application approved successfully.";
} else {
    $_SESSION['errors'] = ["Failed to approve loan application."];
}

$conn->close();

header("Location: ../view/loanOfficersManageClientLoans.php?client_id=$client_id");
exit();
?>