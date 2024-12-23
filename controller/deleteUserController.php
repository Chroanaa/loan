<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../view/login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: ../view/adminDashboard.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$user_id = $_GET['user_id'];
$sql = "DELETE FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['errors'] = ["Failed to delete user."];
}

$stmt->close();
$conn->close();

header("Location: ../view/adminManageUsers.php");
exit();
?>