<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch all notifications
$sql = "SELECT n.notification_id, n.client_id, n.notification_type, n.message, n.notification_date, n.is_read, u.name AS client_name 
        FROM notifications n
        JOIN user_tbl u ON n.client_id = u.user_id";
$result = $conn->query($sql);
$notifications = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarAdmin.php'; ?>
    <div class="container shadow-sm table-responsive border rounded-3 p-5 mt-5">
        <h2>Notifications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Notification ID</th>
                    <th>Client Name</th>
                    <th>Notification Type</th>
                    <th>Message</th>
                    <th>Notification Date</th>
                    <th>Is Read</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                <tr>
                    <td><?php echo htmlspecialchars($notification['notification_id']); ?></td>
                    <td><?php echo htmlspecialchars($notification['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($notification['notification_type']); ?></td>
                    <td><?php echo htmlspecialchars($notification['message']); ?></td>
                    <td><?php echo htmlspecialchars($notification['notification_date']); ?></td>
                    <td><?php echo htmlspecialchars($notification['is_read'] ? 'Yes' : 'No'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>