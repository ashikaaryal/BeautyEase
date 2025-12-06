<?php
session_start();
include('../includes/connect.php');

// Uncomment this when admin login is ready
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
//   header("Location: ../login.php");
//   exit();
// }

// Count users
$user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$user_count = mysqli_fetch_assoc($user_result)['total'] ?? 0;

// Count services
$service_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM services");
$service_count = mysqli_fetch_assoc($service_result)['total'] ?? 0;

// Count appointments
$appointment_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$appointment_count = mysqli_fetch_assoc($appointment_result)['total'] ?? 0;

// Fetch all users for “Manage Users” view (if needed)
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - BeautiEase</title>

  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      background: #f9f9fb;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background: #ff5e8e;
      color: white;
      height: 100vh;
      padding: 30px 20px;
      position: fixed;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 20px 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      transition: 0.3s;
    }

    .sidebar ul li a:hover {
      color: #ffe1eb;
      margin-left: 5px;
    }

    /* Main Content */
    .main-content {
      margin-left: 220px;
      padding: 30px;
      width: calc(100% - 220px);
    }

    /* Header */
    .header {
      background: #fff;
      padding: 15px 25px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      color: #ff5e8e;
    }

    /* Cards */
    .cards {
      display: flex;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .card {
      background: white;
      flex: 1;
      min-width: 220px;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .card h3 {
      color: #555;
      font-weight: 600;
    }

    .card p {
      font-size: 24px;
      color: #ff5e8e;
      margin-top: 10px;
    }

    /* Chart Section */
    .chart-section {
      margin-top: 40px;
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
      .main-content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>BeautiEase</h2>
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="manageuser.php">Manage Users</a></li>
      <li><a href="manage_appointments.php">Appointments</a></li>
      <li><a href="../services.php">Services</a></li>

      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Admin Dashboard</h1>
      <div class="admin-info">
        <p>Welcome, Admin 👩‍💼</p>
      </div>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $user_count; ?></p>
      </div>
      <div class="card">
        <h3>Total Services</h3>
        <p><?php echo $service_count; ?></p>
      </div>
      <div class="card">
        <h3>Total Appointments</h3>
        <p><?php echo $appointment_count; ?></p>
      </div>
    </div>

    <div class="chart-section">
      <h2>Activity Overview</h2>
      <canvas id="activityChart"></canvas>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('activityChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Users', 'Services', 'Appointments'],
        datasets: [{
          label: 'Statistics',
          data: [<?php echo $user_count; ?>, <?php echo $service_count; ?>, <?php echo $appointment_count; ?>],
          borderWidth: 1,
          backgroundColor: ['#ff5e8e', '#ff9bb5', '#ffa3c1']
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
</body>
</html>
