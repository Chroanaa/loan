<?php 
$errors = []; // Initialize an array for errors

if (isset($_POST["login"])) {
    $identifier = $_POST["identifier"]; // Can be either email or username
    $password = $_POST["password"];
    $rememberMe = isset($_POST["remember"]); // Check if the 'Remember Me' checkbox was checked

    // Validate identifier and password
    if (empty($identifier) || empty($password)) {
        $errors[] = "Both fields are required.";
    }

    if (!filter_var($identifier, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]+$/', $identifier)) {
        $errors[] = "Invalid email or username format.";
    }

    if (empty($errors)) {
        // Connect to the database
        require_once "../model/db.php";

        $sql = "SELECT * FROM user_tbl WHERE email = ? OR username = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['show_modal'] = true;
                    
                    // If "Remember Me" is checked
                    if ($rememberMe) {
                        // Generate a token and set cookie
                        $token = bin2hex(random_bytes(64)); // Generate a random token
                        $expiry = time() + 60 * 60 * 24 * 30; // Cookie expires in 30 days
                        setcookie("remember_me", $token, $expiry, "/", "", false, true);

                        // Save the token in the database associated with the user
                        $updateSql = "UPDATE user_tbl SET remember_token = ? WHERE email = ? OR username = ?";
                        $updateStmt = mysqli_stmt_init($conn);

                        if (mysqli_stmt_prepare($updateStmt, $updateSql)) {
                            mysqli_stmt_bind_param($updateStmt, "sss", $token, $identifier, $identifier);
                            mysqli_stmt_execute($updateStmt);
                        }
                    }

                    // Redirect based on role
                    if ($row['role'] == 'Loan Officers') {
                        header("Location: loanOfficersDashboard.php");
                    } elseif ($row['role'] == 'admin') {
                        header("Location: adminDashboard.php");
                    } elseif ($row['role'] == 'Branch Managers') {
                        header("Location: branchManagersDashboard.php");
                    } elseif ($row['role'] == 'client') {
                        header("Location: clientDashboard.php");
                    } else {
                        $errors[] = "Invalid role.";
                    }
                    exit();
                } else {
                    $errors[] = "Incorrect password.";
                }
            } else {
                $errors[] = "No account found with that email or username.";
            }
        } else {
            $errors[] = "Database query failed.";
        }
    }
}

// Handle automatic login if remember token exists
if (isset($_COOKIE["remember_me"])) {
    $token = $_COOKIE["remember_me"];
    require_once "../model/db.php";
    
    // Look for a user with the given token
    $sql = "SELECT * FROM user_tbl WHERE remember_token = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['show_modal'] = true;
            
            // Redirect based on role
            if ($row['role'] == 'Loan Officers') {
                header("Location: loanOfficersDashboard.php");
            } elseif ($row['role'] == 'admin') {
                header("Location: adminDashboard.php");
            } elseif ($row['role'] == 'Branch Managers') {
                header("Location: branchManagersDashboard.php");
            } elseif ($row['role'] == 'client') {
                header("Location: clientDashboard.php");
            } else {
                $errors[] = "Invalid role.";
            }
            exit();
        }
    }
}

// Return errors to be displayed in the form
return $errors;
?>