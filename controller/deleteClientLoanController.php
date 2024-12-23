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
require_once "../model/db.php";

$loan_id = $_GET['loan_id'];
$client_id = $_SESSION['user_id'];

// Delete loan application
$sql = "DELETE FROM loan_applications WHERE loan_id = ? AND client_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $loan_id, $client_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Loan application deleted successfully.";
} else {
    $_SESSION['errors'] = ["Failed to delete loan application."];
}

$stmt->close();
$conn->close();

header("Location: ../view/clientDashboard.php");
exit();
?>