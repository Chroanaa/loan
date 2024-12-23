<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once "../model/db.php";

// Fetch transactions for the current week
$sql = "SELECT l.loan_id, u.name AS client_name, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, b.branch_name
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id
        WHERE YEARWEEK(l.created_at, 1) = YEARWEEK(CURDATE(), 1)";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch overdue loans for the current week
$sql = "SELECT l.loan_id, u.name AS client_name, l.loan_amount, l.loan_term, l.interest_rate, l.status, l.created_at, l.updated_at, b.branch_name
        FROM loan_applications l
        JOIN user_tbl u ON l.client_id = u.user_id
        JOIN branches b ON u.branch_id = b.branch_id
        WHERE l.status = 'Approved' AND DATE_ADD(l.updated_at, INTERVAL l.loan_term MONTH) < NOW() AND YEARWEEK(l.updated_at, 1) = YEARWEEK(CURDATE(), 1)";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$overdue_loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();

// Check if data is fetched correctly
if (empty($loans) && empty($overdue_loans)) {
    echo "No data found for the current week.";
    exit();
}

// Generate CSV report
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=weekly_report.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('Loan ID', 'Client Name', 'Amount', 'Term (months)', 'Interest Rate (%)', 'Status', 'Branch Name', 'Created At', 'Updated At'));

// Write loan data
foreach ($loans as $loan) {
    fputcsv($output, array(
        $loan['loan_id'],
        $loan['client_name'],
        $loan['loan_amount'],
        $loan['loan_term'],
        $loan['interest_rate'],
        $loan['status'],
        $loan['branch_name'],
        $loan['created_at'],
        $loan['updated_at']
    ));
}

// Add an empty line for separation
fputcsv($output, array());

// Add a header for overdue loans
fputcsv($output, array('Overdue Loans'));

// Write overdue loan data
foreach ($overdue_loans as $loan) {
    fputcsv($output, array(
        $loan['loan_id'],
        $loan['client_name'],
        $loan['loan_amount'],
        $loan['loan_term'],
        $loan['interest_rate'],
        $loan['status'],
        $loan['branch_name'],
        $loan['created_at'],
        $loan['updated_at']
    ));
}

fclose($output);
exit();
?>