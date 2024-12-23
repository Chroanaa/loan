<?php
// Connect to the database
require_once "../model/db.php";

// Fetch overdue loans
$sql = "SELECT l.loan_id, l.client_id, u.email, u.name, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        WHERE l.status = 'Approved' AND DATE_ADD(l.updated_at, INTERVAL l.loan_term MONTH) < NOW()";
$result = $conn->query($sql);
$overdueLoans = $result->fetch_all(MYSQLI_ASSOC);

foreach ($overdueLoans as $loan) {
    // Generate notification message
    $message = "Dear {$loan['name']}, your loan with ID {$loan['loan_id']} is overdue. Please make a payment as soon as possible.";

    // Insert notification into the database
    $sql = "INSERT INTO notifications (client_id, notification_type, message) VALUES (?, 'Overdue Reminder', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $loan['client_id'], $message);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect back to the loan officer's dashboard
header("Location: loanOfficersDashboard.php");
exit();
?>