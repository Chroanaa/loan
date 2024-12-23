<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../view/login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];
$branch_id = $_POST['branch_id'];

// Check if username or email already exists
$sql = "SELECT * FROM user_tbl WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['errors'] = ["Username or email already exists."];
    header("Location: ../view/adminCreateAccount.php");
    exit();
}

$stmt->close();

// Insert new user
$sql = "INSERT INTO user_tbl (name, username, email, password, role, branch_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $name, $username, $email, $password, $role, $branch_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Account created successfully.";
} else {
    $_SESSION['errors'] = ["Failed to create account."];
}

$stmt->close();
$conn->close();

header("Location: ../view/adminCreateAccount.php");
exit();
?>