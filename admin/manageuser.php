<?php
session_start();
include('../includes/connect.php');

// --- Uncomment after admin login is ready ---
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
//   header("Location: ../login.php");
//   exit();
// }

// DELETE USER
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM users WHERE id=$id");
  echo "<script>alert('User deleted successfully!'); window.location='manage_users.php';</script>";
}
// PROMOTE USER TO ADMIN
if (isset($_GET['make_admin'])) {
  $id = intval($_GET['make_admin']);
  mysqli_query($conn, "UPDATE users SET role='Admin' WHERE id=$id");
  echo "<script>alert('User promoted to Admin successfully!'); window.location='manage_users.php';</script>";
}

// FETCH USERS
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - BeautiEase</title>
</head>
<body style="font-family:'Poppins',sans-serif; margin:0; background:#f9f9fb; display:flex;">

  <!-- Sidebar -->
  <div style="width:220px; background:#ff5e8e; color:#fff; height:100vh; padding:30px 20px; position:fixed;">
    <h2 style="text-align:center; margin-bottom:30px;">BeautiEase</h2>
    <ul style="list-style:none; padding:0;">
      <li style="margin:15px 0;"><a href="dashboard.php" style="color:#fff; text-decoration:none;">Dashboard</a></li>
      <li style="margin:15px 0;"><a href="manage_users.php" style="color:#ffe1eb; font-weight:bold; text-decoration:none;">Manage Users</a></li>
      <li style="margin:15px 0;"><a href="manage_services.php" style="color:#fff; text-decoration:none;">Manage Services</a></li>
      <li style="margin:15px 0;"><a href="../manage_appointments.php" style="color:#fff; text-decoration:none;">Appointments</a></li>
      <li style="margin:15px 0;"><a href="../logout.php" style="color:#fff; text-decoration:none;">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div style="margin-left:240px; padding:30px; width:calc(100% - 240px);">
    <div style="background:#fff; padding:20px 25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center;">
      <h1 style="color:#ff5e8e;">Manage Users</h1>
      <p>Welcome, Admin 👩‍💼</p>
    </div>

    <!-- Table Section -->
    <div style="margin-top:30px; background:#fff; padding:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); overflow-x:auto;">
      <h2 style="color:#ff5e8e; margin-bottom:20px;">Registered Users</h2>
      <table style="width:100%; border-collapse:collapse; text-align:center;">
        <thead>
          <tr style="background-color:#ff5e8e; color:white;">
            <th style="padding:10px;">ID</th>
            <th style="padding:10px;">Full Name</th>
            <th style="padding:10px;">Email</th>
            <th style="padding:10px;">Phone</th>
            <th style="padding:10px;">Role</th>
            <th style="padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($users)) { ?>
            <tr style="border-bottom:1px solid #ddd;">
              <td style="padding:10px;"><?php echo $row['id']; ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($row['fullname']); ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($row['email']); ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($row['phone']); ?></td>
              <td style="padding:10px;"><?php echo $row['role']; ?></td>
              <td style="padding:10px;">
                <?php if ($row['role'] != 'Admin') { ?>
                  <a href="?make_admin=<?php echo $row['id']; ?>" style="padding:6px 12px; background:#4caf50; color:#fff; border-radius:6px; text-decoration:none; font-size:14px;">Make Admin</a>
                <?php } ?>
                <a href="?delete=<?php echo $row['id']; ?>" style="padding:6px 12px; background:#e74c3c; color:#fff; border-radius:6px; text-decoration:none; font-size:14px;" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
