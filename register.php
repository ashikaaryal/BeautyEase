<?php
include('includes/connect.php');

if (isset($_POST['submit'])) {
  $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = md5($_POST['password']); 
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);

  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Email already registered. Please login instead.');</script>";
  } else {
    $query = "INSERT INTO users (fullname, email, password, phone, role) 
              VALUES ('$fullname', '$email', '$password', '$phone', 'User')";
    if (mysqli_query($conn, $query)) {
      echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
    } else {
      echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - BeautiEase</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    /* ===== Global Reset ===== */
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

    /* ===== Container ===== */
    .container {
      background: #fff;
      width: 90%;
      max-width: 420px;
      padding: 40px 35px;
      border-radius: 15px;
      box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.7s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Heading ===== */
    .container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #ff5e8e;
      font-weight: 700;
      letter-spacing: 1px;
    }

    /* ===== Form Fields ===== */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #444;
      font-size: 15px;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: 0.3s;
    }

    .form-group input:focus {
      border-color: #ff5e8e;
      box-shadow: 0 0 6px rgba(255, 94, 142, 0.3);
      outline: none;
    }

    /* ===== Button ===== */
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
      transition: all 0.3s ease;
    }

    .form-group button:hover {
      background-color: #e0487b;
      box-shadow: 0 4px 10px rgba(255, 94, 142, 0.4);
      transform: translateY(-2px);
    }

    /* ===== Link ===== */
    .container p {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }

    .container a {
      color: #ff5e8e;
      font-weight: 600;
      text-decoration: none;
    }

    .container a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .container {
        padding: 30px 25px;
      }
      .container h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create an Account</h2>
    <form method="post" onsubmit="return validateForm()">
      <div class="form-group">
        <label for="username">Full Name</label>
        <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Create a password" required>
      </div>

      <div class="form-group">
        <label for="phone">Contact Number</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
      </div>

      <div class="form-group">
        <button type="submit" name="submit">Register</button>
      </div>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>

  <script>
    function validateForm() {
      const password = document.getElementById("password").value;
      if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
