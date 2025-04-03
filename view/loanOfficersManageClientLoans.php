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
function calculateChanceOfApproval($monthlyIncome, $monthlyBusinessIncome) {
    $totalIncome = $monthlyIncome + $monthlyBusinessIncome;

    if ($totalIncome >= 50000) {
        return "High (90%)";
    } elseif ($totalIncome >= 30000) {
        return "Medium (70%)";
    } elseif ($totalIncome >= 15000) {
        return "Low (50%)";
    } else {
        return "Very Low (30%)";
    }
}
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
                    <th>Name</th>
                    <th>Term (months)</th>
                    <th>Interest Rate (%)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Chance of approval</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_term']); ?></td>
                    <td><?php echo htmlspecialchars($loan['interest_rate']); ?></td>
                    <td><?php echo htmlspecialchars($loan['status']); ?></td>
                    <td><?php echo htmlspecialchars($loan['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($loan['updated_at']); ?></td>
                    <td><?php echo calculateChanceOfApproval($loan['monthly_income'], $loan['monthly_business_income']); ?></td>
                    <td>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#view-profile" 
                                        onclick="viewProfile(
                                        '<?php echo htmlspecialchars($loan['dob']); ?>',
                                        '<?php echo htmlspecialchars($loan['gender']); ?>',
                                        '<?php echo htmlspecialchars($loan['marital_status']); ?>',
                                        '<?php echo htmlspecialchars($loan['phonenumber']); ?>',
                                        '<?php echo htmlspecialchars($loan['email']); ?>',
                                        '<?php echo htmlspecialchars($loan['address']); ?>',
                                        '<?php echo htmlspecialchars($loan['employment_status']); ?>',
                                        '<?php echo htmlspecialchars($loan['company_name']); ?>',
                                        '<?php echo htmlspecialchars($loan['monthly_income']); ?>',
                                        '<?php echo htmlspecialchars($loan['valid_id']); ?>',
                                        '<?php echo htmlspecialchars($loan['business_name']); ?>',
                                        '<?php echo htmlspecialchars($loan['business_type']); ?>',
                                        '<?php echo htmlspecialchars($loan['business_address']); ?>',
                                        '<?php echo htmlspecialchars($loan['business_registration']); ?>',
                                        '<?php echo htmlspecialchars($loan['years_operation']); ?>',
                                        '<?php echo htmlspecialchars($loan['monthly_business_income']); ?>',
                                        '<?php echo htmlspecialchars($loan['tax_id']); ?>',
                                        '<?php echo htmlspecialchars($loan['loan_type']); ?>',
                                        '<?php echo htmlspecialchars($loan['loan_amount']); ?>',
                                        '<?php echo htmlspecialchars($loan['monthly_payment']); ?>',
                                        '<?php echo htmlspecialchars($loan['loan_start']); ?>',
                                        '<?php echo htmlspecialchars($loan['loan_end']); ?>'
                                            )">
                                        View
                        </button>
                    </td>
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


    <!-- Modal -->
     <div class="modal" id="view-profile">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Client Profile</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <hr>
                    <h5>Personal Information</h5>
                    <hr>
                <p id="dob">Date of birth:</p>
                <p id="gender">Gender:</p>
                <p id="maritalStatus">Marital Status:</p>
                <p id="phoneNumber">Phone Number:</p>
                <p id="email">Email:</p>
                <p id="address">Address:</p>
                <p id="employmentStatus">Employment Status:</p>
                <p id="companyName">Company Name:</p>
                <p id="monthlyIncome">Monthly Income:</p>
                <p id="validId">Valid Id:</p>
                <hr>
                <h5>Business Information:</h5>
                <hr>
                <p id="businessName">Business Name:</p>
                <p id="businessType">Business Type:</p>
                <p id="businessAddress">Business Address:</p>
                <p id="businessRegistration">Business Registration Number:</p>
                <p id="yearsOperation">Years in operation:</p>
                <p id="monthlyBusinessIncome">Monthly Business Income:</p>
                <p id="taxId">Tax Identification:</p>
                <hr>
                <h5>Loan Information:</h5>
                <hr>
                <p id="loanType">Loan Type:</p>
                <p id="loanAmount">Loan Amount:</p>
                <p id="monthlyPayment">Monthly Payment:</p>
                <p id="loanStart">Loan Start:</p>
                <p id="loanEnd">Loan End:</p>
                </div>
            </div>
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

        new DataTable('#manage', {
            responsive: true
        });


    </script>                            
<script>
function viewProfile(
    dob, gender, maritalStatus, phoneNumber, email, address, employmentStatus, companyName, monthlyIncome, validId,
    businessName, businessType, businessAddress, businessRegistration, yearsOperation, monthlyBusinessIncome, taxId,
    loanType, loanAmount, monthlyPayment, loanStart, loanEnd
) {
    document.getElementById('dob').textContent = "Date of birth: " + dob;
    document.getElementById('gender').textContent = "Gender: " + gender;
    document.getElementById('maritalStatus').textContent = "Marital Status: " + maritalStatus;
    document.getElementById('phoneNumber').textContent = "Phone Number: " + phoneNumber;
    document.getElementById('email').textContent = "Email: " + email;
    document.getElementById('address').textContent = "Address: " + address;
    document.getElementById('employmentStatus').textContent = "Employment Status: " + employmentStatus;
    document.getElementById('companyName').textContent = "Company Name: " + companyName;
    document.getElementById('monthlyIncome').textContent = "Monthly Income: " + monthlyIncome;
    document.getElementById('validId').textContent = "Valid Id: " + validId;

    document.getElementById('businessName').textContent = "Business Name: " + businessName;
    document.getElementById('businessType').textContent = "Business Type: " + businessType;
    document.getElementById('businessAddress').textContent = "Business Address: " + businessAddress;
    document.getElementById('businessRegistration').textContent = "Business Registration Number: " + businessRegistration;
    document.getElementById('yearsOperation').textContent = "Years in operation: " + yearsOperation;
    document.getElementById('monthlyBusinessIncome').textContent = "Monthly Business Income: " + monthlyBusinessIncome;
    document.getElementById('taxId').textContent = "Tax Identification: " + taxId;

    document.getElementById('loanType').textContent = "Loan Type: " + loanType;
    document.getElementById('loanAmount').textContent = "Loan Amount: " + loanAmount;
    document.getElementById('monthlyPayment').textContent = "Monthly Payment: " + monthlyPayment;
    document.getElementById('loanStart').textContent = "Loan Start: " + loanStart;
    document.getElementById('loanEnd').textContent = "Loan End: " + loanEnd;
}
</script>

</body>
</html>