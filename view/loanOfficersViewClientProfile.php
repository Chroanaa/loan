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
$sql = "SELECT u.user_id, u.name, u.email, b.branch_name 
        FROM user_tbl u 
        JOIN branches b ON u.branch_id = b.branch_id 
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container shadow-sm border rounded-3 table-responsive p-5  mt-5">
        <h2>Client Profile</h2>
        <table class="table table-bordered">
            <tr>
                <th>Client ID</th>
                <td><?php echo htmlspecialchars($client['user_id']); ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($client['name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($client['email']); ?></td>
            </tr>
            <tr>
                <th>Branch</th>
                <td><?php echo htmlspecialchars($client['branch_name']); ?></td>
            </tr>
        </table>
        <a href="loanOfficersManageClients.php" class="btn btn-secondary">Back to Manage Clients</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>