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
$user_id = $_SESSION['user_id'];

// Check if the loan application is pending and belongs to the logged-in user
$sql = "SELECT * FROM loan_applications WHERE loan_id = ? AND client_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $loan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();

if ($loan) {
    // Delete the loan application
    $sql = "DELETE FROM loan_applications WHERE loan_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header("Location: ../view/clientDashboard.php");
exit();
?>