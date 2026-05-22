<?php
include('includes/connect.php');

if (isset($_POST['submit'])) {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    // ===== Full Name Validation =====
    if (empty($fullname) || !preg_match("/^[a-zA-Z\s]+$/", $fullname)) {
        echo "<script>alert('Full name must contain only letters and spaces.');</script>";
        exit();
    }

    // ===== Phone Validation (10 digits only) =====
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Phone number must be exactly 10 digits.');</script>";
        exit();
    }

    // ===== Standard Email Validation (Accepts all valid emails) =====
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address.');</script>";
        exit();
    }

    // ===== Password Validation =====
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        echo "<script>alert('Password must contain at least 1 uppercase letter, 1 number, and minimum 8 characters.');</script>";
        exit();
    }

    // ===== Check if Email Already Exists =====
    $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "<script>alert('Email already registered. Please login instead.');</script>";
    } else {

        // ===== Secure Password Hashing =====
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = mysqli_prepare($conn, "INSERT INTO users (fullname, email, password, phone, role) VALUES (?, ?, ?, ?, 'User')");
        mysqli_stmt_bind_param($insertStmt, "ssss", $fullname, $email, $hashed_password, $phone);

        if (mysqli_stmt_execute($insertStmt)) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }

        mysqli_stmt_close($insertStmt);
    }

    mysqli_stmt_close($checkStmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - BeautiEase</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background: linear-gradient(135deg, #ff9bb5, #ff5e8e);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    background: #fff;
    width: 90%;
    max-width: 420px;
    padding: 40px 35px;
    border-radius: 15px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
}

.container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #ff5e8e;
    font-weight: 700;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
}

.form-group input:focus {
    border-color: #ff5e8e;
    outline: none;
}

.form-group button {
    width: 100%;
    padding: 12px;
    background-color: #ff5e8e;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}

.form-group button:hover {
    background-color: #e0487b;
}

.container p {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.container a {
    color: #ff5e8e;
    text-decoration: none;
    font-weight: 600;
}
</style>
</head>

<body>

<div class="container">
<h2>Create an Account</h2>

<form method="post" onsubmit="return validateForm()">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullname" id="fullname" required>
    </div>

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div class="form-group">
        <label>Contact Number</label>
        <input type="tel" name="phone" id="phone" required>
    </div>

    <div class="form-group">
        <button type="submit" name="submit">Register</button>
    </div>

</form>

<p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
function validateForm() {

    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const password = document.getElementById("password").value;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^[0-9]{10}$/;
    const passPattern  = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert("Phone number must be exactly 10 digits.");
        return false;
    }

    if (!passPattern.test(password)) {
        alert("Password must contain at least 1 uppercase letter, 1 number, minimum 8 characters.");
        return false;
    }

    return true;
}
</script>

</body>
</html>