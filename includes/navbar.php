<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BeautiEase</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav>
  <a href="index.php" class="logo">BeautyEase</a>

  <ul class="nav-links" id="navLinks">
      <li><a href="index.php">Home</a></li>
      <!-- <li><a href="about.php">About</a></li> -->
      <li><a href="services.php">Services</a></li>
      <li><a href="contact.php">Contact</a></li>

      <?php if (isset($_SESSION['email'])): ?>
          <!-- Logged In -->
          <li><a href="user.php">My profile</a></li>
          <li><a href="logout.php" class="logout-btn" style="color:red;font-weight:bold;">Logout</a></li>
      <?php else: ?>
          <!-- Guest -->
          <li><a href="login.php" class="login-btn">Login</a></li>
      <?php endif; ?>
  </ul>

  <div class="menu-toggle" onclick="toggleMenu()">
    <div></div>
    <div></div>
    <div></div>
  </div>
</nav>

<script>
  function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("active");
  }
</script>

</body>
</html>
