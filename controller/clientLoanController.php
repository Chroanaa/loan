<?php
session_start();
$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $maritalStatus = $_POST['maritalStatus'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $employmentStatus = $_POST['employmentStatus'];
    $companyName = $_POST['companyName'];
    $monthlyIncome = $_POST['monthlyIncome'];
    $validId = $_POST['valid-id'];
    $business_name = $_POST['businessName'];
    $business_type = $_POST['businessType'];
    $business_address = $_POST['businessAddress'];
    $business_registration_num = $_POST['businessRegNumber'];
    $years_of_operation = $_POST['yearsInOperation'];
    $monthly_business_income = $_POST['monthlyBusinessIncome'];
    $tax_id = $_POST['taxIdentification'];
    $loan_type = $_POST['loanType'];
    $loan_amount = $_POST['loanAmount'];
    $loan_term = $_POST['loanTerm'];
    $interest_rate = $_POST['interestRate'];
    $monthly_payment = $_POST['monthlyPayment'];
    $loan_start = $_POST['loanStartDate'];
    $loan_end = $_POST['loanEndDate'];
    $status = 'Pending'; // Default status

    // Validate input
    if (empty($loan_amount) || empty($loan_term) || empty($interest_rate)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Connect to the database
        require_once "../model/db.php";

        // Insert loan application into the database
      
$sql = "INSERT INTO loan_applications (client_id, fullname, dob, gender, marital_status, phonenumber, email, address, employment_status,
    company_name, monthly_income, valid_id, business_name, business_type, business_address, business_registration, years_operation,
    monthly_business_income, tax_id, loan_type, loan_amount, loan_term, interest_rate, monthly_payment, loan_start, loan_end, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issssssssssssssssdssdiddsss",
    $user_id,
    $fullName,
    $dob,
    $gender,
    $maritalStatus,
    $phone,
    $email,
    $address,
    $employmentStatus,
    $companyName,
    $monthlyIncome,
    $validId,
    $business_name,
    $business_type,
    $business_address,
    $business_registration_num,
    $years_of_operation,
    $monthly_business_income,
    $tax_id,
    $loan_type,
    $loan_amount,
    $loan_term,
    $interest_rate,
    $monthly_payment,
    $loan_start,
    $loan_end,
    $status
);

        if ($stmt->execute()) {
            $successMessage = "Loan application submitted successfully.";
        } else {
            $errors[] = "Failed to submit loan application.";
        }

        $stmt->close();
        $conn->close();
    }
}

return $errors;
?>