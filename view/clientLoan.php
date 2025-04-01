<?php
$errors = require '../controller/clientLoanController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Loan Application</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include 'components/navbarClient.php'; ?>
    <div class="container shadow-sm border rounded-3 p-5 my-5">
        <h2>Client Loan Application</h2>
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
            <hr>
            <h4>Personal Information</h4>
            <div class="mb-3">
                <label for="">Full Name:</label>
                <input type="text" name="fullName" class="form-control" placeholder="e.g., John Doe" required>
            </div>
            <div class="mb-3">
                <label for="">Date of Birth:</label>
                <input type="date" name="dob" class="form-control"  required>
            </div>
            <div class="mb-3">
                <label for="">Gender:</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Marital Status</label>
                <select name="maritalStatus" id="maritalStatus" class="form-select" required>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Phone Number:</label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g., 123-456-7890" required>
            </div>
            <div class="mb-3">
                <label for="">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="e.g., johndoe@gmail.com" required>
            </div>
            <div class="mb-3">
                <label for="">Address:</label>
                <input type="text" name="address" class="form-control" placeholder="e.g., 123 Main St, City, State" required>
            </div>
            <div class="mb-3">
                <label for="">Employment Status:</label>
                <select name="employmentStatus" id="employmentStatus" class="form-select" required>
                    <option value="employed">Employed</option>
                    <option value="unemployed">Unemployed</option>
                <select>
            </div>
            <div class="mb-3">
                <label for="">Company Name:</label>
                <input type="text" name="companyName" class="form-control" placeholder="e.g., ABC Corp" required>
            </div>
            <div class="mb-3">
                <label for="">Monthly Income</label>
                <input type="number" name="monthlyIncome" class="form-control" placeholder="e.g., 5000" required>
            </div>
            <div class="mb-3">
                <label for="">Valid ID</label>
                <select name="valid-id" id="valid-id" class="form-select" required>
                    <option value="driver-license">Driver License</option>
                    <option value="voters-id">Voters</option>
                    <option value="sss">SSS</option>
                    <option value="passport">Passport</option>
                    <option value="philhealth">Philhealth</option>
                    <option value="senior">Senior Citizen</option>
                    <option value="gsis">GSIS</option>
                </select>
            </div>
          
            <hr>
            <h4>Business Information</h4>
            <div class="mb-3">
                <label for="">Business Name:</label>
                <input type="text" name="businessName" class="form-control" placeholder="e.g., John's Bakery" required>
            </div>
            <div class="mb-3">
                <label for="">Business Type</label>
                <input type="text" name="businessType" class="form-control" placeholder="e.g., Bakery, Retail" required>
            </div>
            <div class="mb-3">
                <label for="">Business Address:</label>
                <input type="text" name="businessAddress" class="form-control" placeholder="e.g., 456 Elm St, City, State" required>
            </div>
            <div class="mb-3">
                <label for="">Business Registration Number:</label>
                <input type="text" name="businessRegNumber" class="form-control" placeholder="e.g., 123456789" required>
            </div>
            <div class="mb-3">
                <label for="">Years In Operation:</label>
                <input type="number" name="yearsInOperation" class="form-control" placeholder="e.g., 5" required>
            </div>
            <div class="mb-3">
                <label for="">Monthly Business Income:</label>
                <input type="number" name="monthlyBusinessIncome" class="form-control" placeholder="e.g., 10000" required>
            </div>
            <div class="mb-3">
                <label for="">Tax Identification</label>
                <input type="text" name="taxIdentification" class="form-control" placeholder="e.g., 123-456-789" required>
            </div>
            <hr>
            <h4>Loan Details</h4>
            <div class="mb-3">
                <label for="">Is this your first loan:</label>
                <select name="first_loan" id="check_if_first_loan">
                    <option value="" selected>yes or no</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Loan Type:</label>
                <input type="text" name="loanType" class="form-control" placeholder="e.g., Personal, Business" required>
            </div>
            <div class="mb-3">
                <label for="">Loan Amount:</label>
                <input type="number" name="loanAmount" id = "loanAmount" class="form-control" placeholder="e.g., 50000" required>
            </div>
            <div class="mb-3">
                <label for="">Loan Term:</label>
                <input type="text" name="loanTerm" class="form-control" placeholder="e.g., 12 months" required>
            </div>
            <div class="mb-3">
                <label for="">Interest Rate:</label>
                <input type="text" name="interestRate" class="form-control" placeholder="e.g., 5%" required>
            </div>
            <div class="mb-3">
                <label for="">Monthly Payment:</label>
                <input type="text" name="monthlyPayment" class="form-control" placeholder="e.g., 1000" required>
            </div>
            <div class="mb-3">
                <label for="">Loan Start Date:</label>
                <input type="date" name="loanStartDate" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="">Loan End Date:</label>
                <input type="date" name="loanEndDate" class="form-control" required>
            </div>
          
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
        const firstLoan = document.getElementById('check_if_first_loan');
        firstLoan.addEventListener('change', function() {
            if (this.value === 'yes') {
                document.querySelector('#loanAmount').value = 5000
            }
            else if (this.value === 'no') {
                document.querySelector('#loanAmount').value = ""
            }   
        });
    </script>
</body>
</html>