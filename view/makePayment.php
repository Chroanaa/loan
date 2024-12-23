<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['loan_id']) || !isset($_GET['client_id'])) {
    header("Location: loanOfficersManageClients.php");
    exit();
}

$loan_id = $_GET['loan_id'];
$client_id = $_GET['client_id'];

$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    require_once "../model/db.php";

    $payment_amount = $_POST['payment_amount'];

    if (empty($payment_amount)) {
        $errors[] = "Payment amount is required.";
    }

    if (empty($errors)) {
        // Fetch loan details
        $sql = "SELECT * FROM loan_applications WHERE loan_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $loan = $result->fetch_assoc();
        $stmt->close();

        if (!$loan) {
            $errors[] = "Loan not found.";
        } else {
            // Calculate total payments made so far
            $sql = "SELECT SUM(payment_amount) AS total_paid FROM loan_repayments WHERE loan_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $loan_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $total_paid = $result->fetch_assoc()['total_paid'];
            $stmt->close();

            // Calculate remaining balance
            $remaining_balance = $loan['loan_amount'] - ($total_paid + $payment_amount);

            // Insert payment into the database
            $sql = "INSERT INTO loan_repayments (loan_id, payment_amount, remaining_balance) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("idd", $loan_id, $payment_amount, $remaining_balance);

            if ($stmt->execute()) {
                // Update loan status if fully paid
                if ($remaining_balance <= 0) {
                    $sql = "UPDATE loan_applications SET status = 'Paid' WHERE loan_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $loan_id);
                    $stmt->execute();
                    $stmt->close();
                }

                // Insert notification for payment
                $message = "A payment of $payment_amount has been made for your loan with ID $loan_id.";
                $sql = "INSERT INTO notifications (client_id, notification_type, message) VALUES (?, 'Payment Reminder', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $client_id, $message);
                $stmt->execute();
                $stmt->close();

                $successMessage = "Payment made successfully.";
            } else {
                $errors[] = "Failed to make payment.";
            }

       
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container mt-5">
        <h2>Make Payment</h2>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        if (!empty($successMessage)) {
            echo "<div class='alert alert-success'>$successMessage</div>";
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="payment_amount" class="form-label">Payment Amount</label>
                <input type="number" step="0.01" class="form-control" id="payment_amount" name="payment_amount" required>
            </div>
            <button type="submit" class="btn btn-primary">Make Payment</button>
        </form>
        <a href="loanOfficersManageClientLoans.php?client_id=<?php echo $client_id; ?>" class="btn btn-primary mt-3">Back to Client Loans</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>