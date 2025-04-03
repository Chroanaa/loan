<?php 
session_start();
if (isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch all loan applications with branch names
$sql = "SELECT l.loan_id, l.client_id, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, b.branch_name, u.name AS client_name 
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($sql);
$loans = $result->fetch_all(MYSQLI_ASSOC);

// Fetch all loan repayments with branch names
$sql = "SELECT lr.repayment_id, lr.loan_id, lr.payment_amount, lr.payment_date, lr.remaining_balance, b.branch_name, u.name AS client_name 
        FROM loan_repayments lr
        JOIN loan_applications l ON lr.loan_id = l.loan_id
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($sql);
$repayments = $result->fetch_all(MYSQLI_ASSOC);

// Fetch overdue loans
$sql = "SELECT l.loan_id, l.client_id, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, b.branch_name, u.name AS client_name 
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id
        WHERE l.status = 'Approved' AND DATE_ADD(l.updated_at, INTERVAL l.loan_term MONTH) < NOW()";
$result = $conn->query($sql);
$overdue_loans = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">



</head>
<style>
    * {
        font-family: 'Roboto', sans-serif;
    }
    body {
        min-width: 857px;
    }
</style>
<body>
    <?php include 'components/navbarAdmin.php'; ?>
    <div class="container-fluid px-5 py-5" style="min-height: 100vh; background: url(../wwwroot/img/hero-bg.jpg) no-repeat center / cover">

    <div class="container-fluid d-flex justify-content-around align-items-center flex-wrap text-light">
        <h2>Welcome to the Admin Dashboard</h2>

        <div class="box">
            
            <h3 class="mt-4">Generate Reports</h3>
                <a href="adminGenerateWeekly.php" class="btn btn-primary">Generate Weekly Report</a>
                <a href="adminGenerateMonthly.php" class="btn btn-primary">Generate Monthly Report</a>
                <a href="adminGenerateAnnually.php" class="btn btn-primary">Generate Annually Report</a>
        </div>

    </div>

        <div class="shadow-sm border container-fluid p-4 bg-light mt-3 rounded-3">
            <h3 class="mt-4">Loan Applications</h3>
            <table class="table table-bordered" id="loansTable">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Client Name</th>
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
                        <td><?php echo htmlspecialchars($loan['client_name']); ?></td>
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
        </div>


        <section class="d-flex justify-content-center align-items-center" style="gap: 1rem" id="mid-section">

            <div class="shadow-sm bg-light border table-responsive p-4 mt-3 rounded-3" style="flex: 1">
                <h3 class="mt-4">Loan Repayments</h3>
                <table class="table table-bordered" id="repaymentsTable">
                    <thead>
                        <tr>
                            <th>Repayment ID</th>
                            <th>Loan ID</th>
                            <th>Client Name</th>
                            <th>Payment Amount</th>
                            <th>Payment Date</th>
                            <th>Remaining Balance</th>
                            <th>Branch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repayments as $repayment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($repayment['repayment_id']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['loan_id']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['payment_amount']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['remaining_balance']); ?></td>
                            <td><?php echo htmlspecialchars($repayment['branch_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="shadow-sm bg-light border table-responsive p-4 mt-3 rounded-3" style="flex: 1">
                <h3 class="mt-4">Overdue Loans</h3>
                    <table class="table table-bordered" id="overdueTable">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Client Name</th>
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
                            <?php foreach ($overdue_loans as $loan): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                                <td><?php echo htmlspecialchars($loan['client_name']); ?></td>
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
            </div>
        </section>


    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>


    <script>

        new DataTable('#loansTable', {
            responsive: true
        });
        new DataTable('#repaymentsTable', {
            responsive: true
        });
        new DataTable('#overdueTable', {
            responsive: true
        });

    </script>                            





</body>
</html>