<?php
include('includes/connect.php');
session_start();

if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  // Validation
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address.');</script>";
    exit();
  }

  if (empty($password)) {
    echo "<script>alert('Please enter your password.');</script>";
    exit();
  }

  $hashed_password = md5($password);

  $stmt = mysqli_prepare($conn, "SELECT id, role, email FROM users WHERE email = ? AND password = ?");
  mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $user_id, $role, $user_email);
    mysqli_stmt_fetch($stmt);

    // SESSION SET
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;
    $_SESSION['email'] = $user_email;

    // COOKIE SET (Remember Me)
    if (isset($_POST['remember'])) {
        setcookie("user_email", $user_email, time() + (86400 * 7), "/");
        setcookie("user_role", $role, time() + (86400 * 7), "/");
    }

    // REDIRECT
    if ($role == 'Admin') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }

  } else {
    echo "<script>alert('Invalid login details.');</script>";
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - BeautiEase</title>

  <style>
/* ==== Global Reset ==== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ==== Body ==== */
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #ff5e8e, #ff9bb5);
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* ==== Container ==== */
.container {
  background: #fff;
  width: 100%;
  max-width: 400px;
  padding: 40px 30px;
  border-radius: 15px;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
  text-align: center;
  animation: fadeIn 0.6s ease;
}

/* ==== Heading ==== */
.container h2 {
  color: #ff5e8e;
  font-size: 28px;
  margin-bottom: 25px;
  font-weight: 700;
  letter-spacing: 1px;
}

/* ==== Input Fields ==== */
.container input[type="email"],
.container input[type="password"] {
  width: 100%;
  padding: 12px;
  margin: 10px 0 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
}

.container input:focus {
  border-color: #ff5e8e;
  outline: none;
  box-shadow: 0 0 6px rgba(255, 94, 142, 0.4);
}

/* ==== Button ==== */
.btn {
  width: 100%;
  padding: 12px;
  background-color: #ff5e8e;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn:hover {
  background-color: #e0487b;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(255, 94, 142, 0.4);
}

/* ==== Link Section ==== */
.container p {
  margin-top: 20px;
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

/* ==== Animation ==== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==== Responsive ==== */
@media (max-width: 480px) {
  .container {
    width: 90%;
    padding: 30px 20px;
  }
  .container h2 {
    font-size: 24px;
  }
}
  </style>
</head>

<body>

  <div class="container">
    <h2>Login</h2>
    <form method="POST">

      <input type="email" name="email" placeholder="Enter Email" required>
      <input type="password" name="password" placeholder="Enter Password" required>

      <label style="float:left; margin-bottom:10px;">
        <input type="checkbox" name="remember"> Remember Me
      </label>
      <br><br>

      <button type="submit" name="login" class="btn">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>

</body>
</html>
