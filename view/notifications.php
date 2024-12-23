<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$user_id = $_SESSION['user_id'];

// Mark notifications as read
$sql = "UPDATE notifications SET is_read = 1 WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Fetch client notifications
$sql = "SELECT * FROM notifications WHERE client_id = ? ORDER BY notification_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
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
<?php include 'components/navbarClient.php'; ?>
    <div class="container mt-5">
        <h2>Your Notifications</h2>
        <ul class="list-group mb-4">
            <?php foreach ($notifications as $notification): ?>
            <li class="list-group-item <?php echo $notification['is_read'] ? 'list-group-item-secondary' : 'list-group-item-primary'; ?>">
                <?php echo htmlspecialchars($notification['message']); ?>
                <span class="badge bg-secondary"><?php echo htmlspecialchars($notification['notification_type']); ?></span>
                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($notification['notification_date']); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="clientDashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>