<?php
include('../includes/connect.php');
session_start();
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Appointments | BeautiEase</title>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #d5b1bcff, #ff5e8e);
      margin: 0;
      padding: 20px 0;
    }
    .container {
      width: 90%;
      max-width: 1000px;
      margin: auto;
      background: #fff;
      border-radius: 15px;
      padding: 25px 30px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; color: #ff5e8e; margin-bottom: 25px; }
    table { width: 100%; border-collapse: collapse; }
    th, td {
      padding: 10px 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      vertical-align: middle;
    }
    th { background-color: #ff5e8e; color: #fff; }
    tr:hover { background-color: #f9f9f9; }
    img.service-img {
      width: 70px; height: 70px;
      border-radius: 10px; object-fit: cover;
      border: 1px solid #ddd;
    }
    .action-btn {
      padding: 6px 10px; border: none;
      border-radius: 6px; cursor: pointer;
      color: #fff; font-weight: 500;
      margin: 2px;
    }
    .delete-btn { background-color: #e74c3c; }
    .approve-btn { background-color: #3498db; }
    .complete-btn { background-color: #27ae60; }
    .no-data {
      text-align: center; font-weight: bold;
      color: #666; margin-top: 20px;
    }
    a.back {
      display: block; text-align: center;
      margin-top: 20px; color: #ff5e8e;
      text-decoration: none; font-weight: 600;
    }
  </style>
</head>

<body>
<div style="width:220px; background:#ff5e8e; color:#fff; height:100vh; padding:30px 20px; position:fixed;">
    <h2 style="text-align:center; margin-bottom:30px;">BeautiEase</h2>
    <ul style="list-style:none; padding:0;">
      <li style="margin:15px 0;"><a href="dashboard.php" style="color:#fff; text-decoration:none;">Dashboard</a></li>
      <li style="margin:15px 0;"><a href="manageuser.php" style="color:#fff; text-decoration:none;">Manage Users</a></li>
      <li style="margin:15px 0;"><a href="manage_services.php" style="color:#ffe1eb; font-weight:bold; text-decoration:none;">Manage Services</a></li>
      <li style="margin:15px 0;"><a href="manage_appointments.php" style="color:#fff; text-decoration:none;">Appointments</a></li>
      <li style="margin:15px 0;"><a href="../logout.php" style="color:#fff; text-decoration:none;">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div style="margin-left:240px; padding:30px; width:calc(100% - 240px);">
    <div style="background:#fff; padding:20px 25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center;">
        <h1 style="color:#ff5e8e;">Manage appointment</h1>
        <p>Welcome, Admin 👩‍💼</p>
    </div>

<?php
// SQL QUERY - Sort by status and date/time
$query = "SELECT b.id, b.address_type, b.address, b.phone, b.date, b.time, b.email, b.status,
                 s.service_name, s.price, s.image
          FROM bookings b
          JOIN services s ON b.service_id = s.id
          ORDER BY FIELD(b.status, 'Pending', 'Approved', 'Completed'), b.date ASC, b.time ASC";

// RUN QUERY
$result = mysqli_query($conn, $query);

// CHECK FOR SQL ERRORS
if (!$result) {
    die("<p style='color:red; font-weight:bold;'>SQL Error: " . mysqli_error($conn) . "</p>");
}

// DISPLAY DATA
if (mysqli_num_rows($result) > 0) {

    echo "<table>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Service</th>
              <th>Price (Rs.)</th>
              <th>Date</th>
              <th>Time</th>
              <th>Services Type</th>
              <th>Address</th>
              <th>User Phone</th>
              <th>Status</th>
              <th>Action</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {

        $imagePath = "../uploads/" . $row['image'];
        $status = $row['status'];

        echo "<tr>
                <td>{$row['id']}</td>
                <td><img src='{$imagePath}' class='service-img' alt='Service Image'></td>
                <td>{$row['service_name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['date']}</td>
                <td>{$row['time']}</td>
                <td>{$row['address_type']}</td>
                <td>{$row['address']}</td>
                <td>{$row['phone']}</td>
                <td><strong>{$status}</strong></td>
                <td>";

        // ---------------------------
        // SHOW BUTTONS BASED ON STATUS
        // ---------------------------

        if ($status == 'Pending') {
            echo "<a href='approve_booking.php?id={$row['id']}&email={$row['email']}&service={$row['service_name']}'>
                    <button class='action-btn approve-btn'>Approve</button>
                  </a>";
        }

        if ($status == 'Approved') {
            echo "<a href='complete.php?id={$row['id']}&email={$row['email']}&service={$row['service_name']}'>
                    <button class='action-btn complete-btn'>Mark Completed</button>
                  </a>";
        }

        if ($status == 'Completed') {
            echo "<button class='action-btn complete-btn' style='background:#2ecc71; cursor:not-allowed;'>Completed ✓</button>";
        }

        // Delete button always visible
        echo "<a href='delete_booking.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this booking?');\">
                <button class='action-btn delete-btn'>Delete</button>
              </a>";

        echo "</td>
              </tr>";
    }

    echo "</table>";

} else {
    echo "<p class='no-data'>No appointments booked yet.</p>";
}
?>

<a href='manageuser.php' class='back'>Dashboard</a>
</div>
</body>
</html>
