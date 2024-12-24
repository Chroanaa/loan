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
    <div class="container shadow-sm border rounded-3 p-5 mt-5">
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
            <div class="mb-3">
                <label for="loan_amount" class="form-label">Loan Amount</label>
                <input type="number" step="0.01" class="form-control" id="loan_amount" name="loan_amount" required>
            </div>
            <div class="mb-3">
                <label for="loan_term" class="form-label">Loan Term (in months)</label>
                <input type="number" class="form-control" id="loan_term" name="loan_term" required>
            </div>
            <div class="mb-3">
                <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>