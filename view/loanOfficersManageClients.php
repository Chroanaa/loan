<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lo') {
    header("Location: login.php");
    exit();
}

// Connect to the database
require_once "../model/db.php";

// Fetch all clients with branch names
$sql = "SELECT u.user_id, u.name, u.email, b.branch_name 
        FROM user_tbl u 
        JOIN branches b ON u.branch_id = b.branch_id 
        WHERE u.role = 'client'";
$result = $conn->query($sql);
$clients = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php include 'components/navbarLoanOfficer.php'; ?>
    <div class="container mt-5">
        <h2>Manage Clients</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Client ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($client['name']); ?></td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td><?php echo htmlspecialchars($client['branch_name']); ?></td>
                    <td>
                        <a href="loanOfficersViewClientProfile.php?client_id=<?php echo $client['user_id']; ?>" class="btn btn-info">View Profile</a>
                        <a href="loanOfficersManageClientLoans.php?client_id=<?php echo $client['user_id']; ?>" class="btn btn-primary">Manage Loans</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>