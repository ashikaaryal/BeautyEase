<?php
session_start();
include('includes/connect.php');

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="assets/style.css">
</head>

<style>
body {
    margin: 0;
    padding: 0;
    background: #f7f7f7;
    font-family: "Poppins", sans-serif;
}

/* Top Gradient Header */
.top-header {
    width: 100%;
    padding: 40px 0;
    background: linear-gradient(135deg,#ff4d88,#ff2e63,#ff6da7);
    text-align: center;
    color: white;
    border-bottom-left-radius: 40px;
    border-bottom-right-radius: 40px;
    box-shadow: 0 4px 20px rgba(255, 0, 80, 0.3);
}

.top-header h2 {
    font-size: 32px;
    margin-bottom: 10px;
    font-weight: 700;
}

.top-header p {
    font-size: 17px;
    opacity: 0.9;
}

/* Main Container */
.container {
    width: 70%;
    margin: 40px auto;
}

/* Glass Cards */
.card {
    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.07);
    transition: 0.25s ease;
    border: 1px solid rgba(255, 140, 180, 0.2);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 30px rgba(0,0,0,0.12);
}

/* Card Heading With Icons */
.card h3 {
    font-size: 22px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card h3 i {
    color: #ff2773;
    font-size: 24px;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 10px 18px;
    background: #ff2e63;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn:hover {
    background: #ff1b54;
    transform: scale(1.06);
}

/* Logout Button */
.logout-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: #222;
    color: white;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s ease;
}

.logout-btn:hover {
    background: #ff0033;
    transform: scale(1.06);
}

/* Profile Info Layout */
.profile-info p {
    font-size: 15px;
    color: #444;
    margin: 5px 0;
}

/* Profile Icon */
.profile-icon-big {
    font-size: 60px;
    color: #ff2e63;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}
</style>

<body>

    <!-- Gradient Header -->
    <div class="top-header">
        <i class="fa-solid fa-circle-user profile-icon-big"></i>
        <h2>Hello, <?php echo $user['fullname']; ?> 👋</h2>
        <p>Welcome back to your dashboard</p>
    </div>

    <div class="container">

        <!-- Profile Section -->
        <div class="card">
            <h3><i class="fa-solid fa-user"></i> Your Profile</h3>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            </div>
            <a href="edit_profile.php" class="btn"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</a>
        </div>

        <!-- Bookings Section -->
        <div class="card">
            <h3><i class="fa-solid fa-calendar-check"></i> Your Bookings</h3>
            <a href="mybookings.php" class="btn"><i class="fa-solid fa-eye"></i> View My Bookings</a>
        </div>

        <!-- Services Section -->
        <div class="card">
            <h3><i class="fa-solid fa-spa"></i> Available Services</h3>
            <a href="services.php" class="btn"><i class="fa-solid fa-list"></i> View Services</a>
        </div>

        <!-- Logout -->
        <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

</body>
</html>
