<?php
session_start();
include('../includes/connect.php');

// Uncomment when admin login is implemented
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
//     header("Location: ../login.php");
//     exit();
// }

$service = null;
$service_id = 0;

// Fetch existing service data if 'id' is in the URL
if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);
    // Use prepared statements
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    if (!$service) {
        echo "<script>alert('Service not found.'); window.location='manage_services.php';</script>";
        exit();
    }
}

// UPDATE SERVICE
if (isset($_POST['update_service'])) {
    $id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
    $image_name = $current_image; // Default to existing image

    // Check if a new image was uploaded
    if ($_FILES['image']['name']) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_path = "../uploads/" . basename($image_name);

        if (move_uploaded_file($image_tmp, $upload_path)) {
            // Delete old image if it exists
            if ($current_image && file_exists("../uploads/$current_image")) {
                unlink("../uploads/$current_image");
            }
        } else {
            echo "<script>alert('Failed to upload new image. Keeping old image.');</script>";
            $image_name = $current_image; // Revert to old image name if upload fails
        }
    }

    // Update the database record using prepared statement
    $stmt = $conn->prepare("UPDATE services SET service_name = ?, price = ?, image = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $name, $price, $image_name, $description, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Service updated successfully!'); window.location='manage_services.php';</script>";
    } else {
        echo "<script>alert('Error updating service.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Service - BeautiEase</title>
</head>
<body style="font-family:'Poppins',sans-serif; margin:0; background:#f9f9fb; display:flex;">

<!-- Sidebar (Same as manage_services.php) -->
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
        <h1 style="color:#ff5e8e;">Edit Service</h1>
        <a href="manage_services.php" style="padding:10px 15px; background:#ccc; color:#000; border-radius:8px; text-decoration:none;">Back to Services</a>
    </div>

    <!-- Edit Service Form -->
    <div style="margin-top:30px; background:#fff; padding:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
      <h2 style="color:#ff5e8e; margin-bottom:20px;">Editing: <?php echo htmlspecialchars($service['service_name']); ?></h2>
      
      <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:15px;">
          <!-- Hidden field to keep track of the service ID being edited -->
          <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
          <!-- Hidden field to keep track of the current image name -->
          <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($service['image']); ?>">

          <input type="text" name="service_name" placeholder="Service Name" required
                 value="<?php echo htmlspecialchars($service['service_name']); ?>"
                 style="padding:10px; border:1px solid #ccc; border-radius:8px;">
          
          <input type="number" name="price" placeholder="Service Price" required
                 value="<?php echo htmlspecialchars($service['price']); ?>"
                 style="padding:10px; border:1px solid #ccc; border-radius:8px;">
          
          <textarea name="description" placeholder="Service Description" required
                    style="padding:10px; border:1px solid #ccc; border-radius:8px;"><?php echo htmlspecialchars($service['description']); ?></textarea>
          
          <p>Current Image:</p>
          <img src="../uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="Service Image" width="150" style="margin-bottom:10px; border-radius:10px;">

          <label for="image">Change Image (optional):</label>
          <input type="file" name="image" accept="image/*"
                 style="padding:8px; border:1px solid #ccc; border-radius:8px;">
          
          <button type="submit" name="update_service"
                  style="padding:10px; background:#ff5e8e; color:#fff; border:none; border-radius:8px; cursor:pointer;">Update Service</button>
      </form>
    </div>
</div>
</body>
</html>
