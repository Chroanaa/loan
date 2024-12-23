<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['client_id'])) {
    header("Location: loanOfficersManageClients.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$client_id = $_GET['client_id'];
$sql = "SELECT * FROM loan_applications WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Client Loans</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container mt-5">
        <h2>Manage Client Loans</h2>
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
                        <a href="../controller/approveLoanController.php?loan_id=<?php echo $loan['loan_id']; ?>&client_id=<?php echo $client_id; ?>" class="btn btn-success">Approve</a>
                        <a href="../controller/rejectLoanController.php?loan_id=<?php echo $loan['loan_id']; ?>&client_id=<?php echo $client_id; ?>" class="btn btn-danger">Reject</a>
                        <a href="../controller/deleteLoanController.php?loan_id=<?php echo $loan['loan_id']; ?>&client_id=<?php echo $client_id; ?>" class="btn btn-danger">Delete</a>
                        <?php elseif ($loan['status'] == 'Approved'): ?>
                        <a href="makePayment.php?loan_id=<?php echo $loan['loan_id']; ?>&client_id=<?php echo $client_id; ?>" class="btn btn-success">Make Payment</a>
                        <a href="../controller/deleteLoanController.php?loan_id=<?php echo $loan['loan_id']; ?>&client_id=<?php echo $client_id; ?>" class="btn btn-danger">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="loanOfficersManageClients.php" class="btn btn-secondary">Back to Manage Clients</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>