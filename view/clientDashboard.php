<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch client loan applications
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM loan_applications WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Fetch client loan repayments
$sql = "SELECT * FROM loan_repayments WHERE loan_id IN (SELECT loan_id FROM loan_applications WHERE client_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repayments = $result->fetch_all(MYSQLI_ASSOC);



// Include notifications
include '../controller/notificationsController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client's Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarClient.php'; ?>
    <div class="container mt-5">
        <h2>Welcome to the Client Dashboard</h2>
        
        <h3 class="mt-4">Your Notifications</h3>
        <ul class="list-group mb-4">
            <?php foreach ($notifications as $notification): ?>
            <li class="list-group-item <?php echo $notification['is_read'] ? 'list-group-item-secondary' : 'list-group-item-primary'; ?>">
                <?php echo htmlspecialchars($notification['message']); ?>
                <span class="badge bg-secondary"><?php echo htmlspecialchars($notification['notification_type']); ?></span>
                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($notification['notification_date']); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>

        <h3>Your Loan Applications</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount</th>
                    <th>Term (months)</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                    <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($loan['status']); ?></td>
                    <td><?php echo htmlspecialchars($loan['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($loan['updated_at']); ?></td>
                    <td>
                        <?php if ($loan['status'] == 'Pending'): ?>
                        <a href="../controller/deleteClientLoanController.php?loan_id=<?php echo $loan['loan_id']; ?>" class="btn btn-danger">Delete</a>
                        <?php elseif ($loan['status'] == 'Approved'): ?>
                        <a href="makePayment.php?loan_id=<?php echo $loan['loan_id']; ?>" class="btn btn-success">Make Payment</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Your Loan Repayments</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Repayment ID</th>
                    <th>Loan ID</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                    <th>Remaining Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repayments as $repayment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($repayment['repayment_id']); ?></td>
                    <td><?php echo htmlspecialchars($repayment['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($repayment['payment_amount']); ?></td>
                    <td><?php echo htmlspecialchars($repayment['payment_date']); ?></td>
                    <td><?php echo htmlspecialchars($repayment['remaining_balance']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>