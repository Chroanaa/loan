<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

$client_id = $_GET['client_id'];

// Fetch client loans
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
        min-height: 100vh;
        background: url(../wwwroot/img/hero-bg.jpg) no-repeat center / cover;
    }
</style>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container bg-light shadow-sm border rounded-3 p-5 mt-5 mb-5">
        <h2>Manage Client Loans</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); // Clear the message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php 
                foreach ($_SESSION['errors'] as $error) {
                    echo $error . "<br>";
                }
                unset($_SESSION['errors']); // Clear the errors after displaying them
                ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered" id="manage">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>


    <script>

        new DataTable('#manage', {
            responsive: true
        });


    </script>                            


</body>
</html>