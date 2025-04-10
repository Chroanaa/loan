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

$stmt->close();

// Fetch unread notifications count
$sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE client_id = ? AND is_read = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$unread_count = $result->fetch_assoc()['unread_count'];

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client's Dashboard</title>
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
    <?php include 'components/navbarClient.php'; ?>
    <div class="container-fluid px-5 mt-5">
    <h2>Welcome to the Client Dashboard</h2>

    <div class="container-fluid shadow-sm border rounded-3 p-5">
        
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


        <h3 class="mt-4">Your Loan Applications</h3>
        <table class="table table-bordered" id="loansTable">
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
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="container-fluid shadow-sm border rounded-3 mt-3 p-5">


        <h3 class="mt-4">Your Loan Repayments</h3>
        <table class="table table-bordered" id="repaymentsTable">
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