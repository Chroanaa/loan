<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch all loan applications with branch names
$sql = "SELECT l.loan_id, l.client_id, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, b.branch_name 
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($sql);
$loans = $result->fetch_all(MYSQLI_ASSOC);

// Fetch all loan repayments
$sql = "SELECT * FROM loan_repayments";
$result = $conn->query($sql);
$repayments = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Officer's Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container mt-5">
        <h2>Welcome to the Loan Officer's Dashboard</h2>
        
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <h3 class="mt-4">Loan Applications</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Client ID</th>
                    <th>Amount</th>
                    <th>Term (months)</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Branch</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['client_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                    <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($loan['status']); ?></td>
                    <td><?php echo htmlspecialchars($loan['branch_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($loan['updated_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Loan Repayments</h3>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>