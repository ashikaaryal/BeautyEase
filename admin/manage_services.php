<?php
session_start();
include('../includes/connect.php');

// Uncomment when admin login is implemented
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
//     header("Location: ../login.php");
//     exit();
// }

// ADD SERVICE
// if (isset($_POST['add_service'])) {
//     $name = mysqli_real_escape_string($conn, $_POST['service_name']);
//     $price = mysqli_real_escape_string($conn, $_POST['price']);
//     $description = mysqli_real_escape_string($conn, $_POST['description']);

//     $image_name = $_FILES['image']['name'];
//     $image_tmp = $_FILES['image']['tmp_name'];
//     $upload_path = "../uploads/" . basename($image_name);


//     if (move_uploaded_file($image_tmp, $upload_path)) {
//         // Prepare statement for security
//         $stmt = $conn->prepare("INSERT INTO services (service_name, price, image, description) VALUES (?, ?, ?, ?)");
//         $stmt->bind_param("sdss", $name, $price, $image_name, $description);
//         $stmt->execute();
//         $stmt->close();
//         echo "<script>alert('Service added successfully!'); window.location='manage_services.php';</script>";
//     } else {
//         echo "<script>alert('Failed to upload image.');</script>";
//     }
// }

// // DELETE SERVICE
// if (isset($_GET['delete'])) {
//     $id = intval($_GET['delete']);
//     // Use prepared statements for security
//     $stmt = $conn->prepare("SELECT image FROM services WHERE id = ?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $img = $result->fetch_assoc()['image'];
//     $stmt->close();
    
//     if($img && file_exists("../uploads/$img")) {
//         unlink("../uploads/$img");
//     }
    
//     $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $stmt->close();
//     echo "<script>alert('Service deleted successfully!'); window.location='manage_services.php';</script>";
// }

// // FETCH SERVICES
// $services = mysqli_query($conn, "SELECT * FROM services ORDER BY id ASC");
// ?>

<?php


// ADD SERVICE
if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);  // Service Type

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_path = "../uploads/" . basename($image_name);

    if (move_uploaded_file($image_tmp, $upload_path)) {
        // Prepare statement for security
        $stmt = $conn->prepare("INSERT INTO services (service_name, price, image, description, service_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $name, $price, $image_name, $description, $service_type);  // Bind service_type
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Service added successfully!'); window.location='manage_services.php';</script>";
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}

// DELETE SERVICE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT image FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $img = $result->fetch_assoc()['image'];
    $stmt->close();
    
    if ($img && file_exists("../uploads/$img")) {
        unlink("../uploads/$img");
    }
    
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Service deleted successfully!'); window.location='manage_services.php';</script>";
}

// FETCH SERVICES
$services = mysqli_query($conn, "SELECT * FROM services ORDER BY id ASC");
?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Services - BeautiEase</title>
</head>
<body style="font-family:'Poppins',sans-serif; margin:0; background:#f9f9fb; display:flex;">

<!-- Sidebar -->
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
        <h1 style="color:#ff5e8e;">Manage Services</h1>
        <p>Welcome, Admin 👩‍💼</p>
    </div>

    <!-- Add Service Form -->
    <div style="margin-top:30px; background:#fff; padding:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
      <h2 style="color:#ff5e8e; margin-bottom:20px;">Add New Service</h2>
      <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:15px;">
          <input type="text" name="service_name" placeholder="Service Name" required
                 style="padding:10px; border:1px solid #ccc; border-radius:8px;">
          <input type="number" name="price" placeholder="Service Price" required
                 style="padding:10px; border:1px solid #ccc; border-radius:8px;">
          <textarea name="description" placeholder="Service Description" required
                    style="padding:10px; border:1px solid #ccc; border-radius:8px;"></textarea>
          
          <!-- Service Type Dropdown -->
          <select name="service_type" required style="padding:10px; border:1px solid #ccc; border-radius:8px;">
              <option value="">-- Choose Service Type --</option>
              <option value="Normal">Normal</option>
              <option value="Medium">Medium</option>
              <option value="Premium">Premium</option>
          </select>
          
          <input type="file" name="image" accept="image/*" required
                 style="padding:8px; border:1px solid #ccc; border-radius:8px;">
          <button type="submit" name="add_service"
                  style="padding:10px; background:#ff5e8e; color:#fff; border:none; border-radius:8px; cursor:pointer;">Add Service</button>
      </form>
    </div>

    <!-- Service Table -->
    <div style="margin-top:30px; background:#fff; padding:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); overflow-x:auto;">
      <h2 style="color:#ff5e8e; margin-bottom:20px;">Available Services</h2>
      <table style="width:100%; border-collapse:collapse; text-align:center;">
        <thead>
          <tr style="background-color:#ff5e8e; color:white;">
            <th style="padding:10px;">ID</th>
            <th style="padding:10px;">Image</th>
            <th style="padding:10px;">Service Name</th>
            <th style="padding:10px;">Price</th>
            <th style="padding:10px;">Service Type</th> <!-- New Column -->
            <th style="padding:10px;">Description</th>
            <th style="padding:10px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($services)) { ?>
            <tr style="border-bottom:1px solid #ddd;">
              <td style="padding:10px;"><?php echo $row['id']; ?></td>
              <td style="padding:10px;"><img src="../uploads/<?php echo $row['image']; ?>" alt="Service Image" width="70" height="70" style="border-radius:10px;"></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($row['service_name']); ?></td>
              <td style="padding:10px;">Rs. <?php echo number_format($row['price'], 2); ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($row['service_type']); ?></td> <!-- Display Service Type -->
              <td style="padding:10px;"><?php echo htmlspecialchars($row['description']); ?></td>
              <td style="padding:10px;">
                <!-- Edit and Delete Links -->
                <a href="edit_service.php?id=<?php echo $row['id']; ?>" 
                   style="padding:6px 12px; background:#3498db; color:#fff; border-radius:6px; text-decoration:none; margin-right:5px;">Edit</a>
                <a href="?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this service?');"
                   style="padding:6px 12px; background:#e74c3c; color:#fff; border-radius:6px; text-decoration:none;">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
</div>
</body>
</html>

