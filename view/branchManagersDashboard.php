<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'bm') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch branch manager's branch ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT branch_id FROM user_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$branch_manager = $result->fetch_assoc();
$branch_id = $branch_manager['branch_id'];
$stmt->close();

// Fetch all loan applications with branch names for the branch manager's branch
$sql = "SELECT l.loan_id, l.client_id, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, u.name AS client_name 
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id
        WHERE u.branch_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch overdue loans for the branch
$sql = "SELECT l.loan_id, l.client_id, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, u.name AS client_name 
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        WHERE u.branch_id = ? AND l.status = 'Approved' AND DATE_ADD(l.updated_at, INTERVAL l.loan_term MONTH) < NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$overdue_loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch loan officers for the branch
$sql = "SELECT user_id, name, email FROM user_tbl WHERE role = 'lo' AND branch_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$loan_officers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager's Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarBranchManager.php'; ?>
    <div class="container mt-5">
        <h2>Welcome to the Branch Manager's Dashboard</h2>

        <h3 class="mt-4">Loan Portfolio</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Client Name</th>
                    <th>Amount</th>
                    <th>Term (months)</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                    <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($loan['status']); ?></td>
                    <td><?php echo htmlspecialchars($loan['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($loan['updated_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Overdue Loans</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Client Name</th>
                    <th>Amount</th>
                    <th>Term (months)</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($overdue_loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_amount']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                    <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($loan['status']); ?></td>
                    <td><?php echo htmlspecialchars($loan['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($loan['updated_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Loan Officers</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_officers as $officer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($officer['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($officer['name']); ?></td>
                    <td><?php echo htmlspecialchars($officer['email']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Generate Reports</h3>
        <a href="branchManagersGenerateWeekly.php" class="btn btn-primary">Generate Weekly Report</a>
        <a href="branchManagersGenerateMonthly.php" class="btn btn-primary">Generate Monthly Report</a>
        <a href="branchManagersGenerateAnnually.php" class="btn btn-primary">Generate Annual Report</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>