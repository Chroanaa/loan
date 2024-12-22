<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: ../view/login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$user_id = $_SESSION['user_id'];

// Fetch unread notifications
$sql = "SELECT * FROM notifications WHERE client_id = ? AND is_read = 0 ORDER BY notification_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);


?>