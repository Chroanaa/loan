<?php
// Fetch unread notifications count
require_once "../model/db.php";
$user_id = $_SESSION['user_id'];
$sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE client_id = ? AND is_read = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$unread_count = $result->fetch_assoc()['unread_count'];
$stmt->close();
$conn->close();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="clientDashboard.php">Client Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="clientLoan.php">Apply for a Loan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="notifications.php">Notifications <span class="badge bg-danger"><?php echo $unread_count; ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../controller/logoutController.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>