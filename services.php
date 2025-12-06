<?php
include('includes/connect.php');
include('includes/navbar.php');

// Fetch all services from the database
$query = "SELECT * FROM services ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Services - BeautiEase</title>
<link rel="stylesheet" href="assets/style.css">
<script>
    // Toggle the service description visibility
    function toggleDescription(id) {
        var moreText = document.getElementById("more-" + id);
        var btnText = document.getElementById("view-details-btn-" + id);

        // If the text is hidden, show it and change the button text
        if (moreText.style.display === "none") {
            moreText.style.display = "inline";
            btnText.innerHTML = "Hide Details";
        } else {
            // Otherwise, hide the text and change the button text back
            moreText.style.display = "none";
            btnText.innerHTML = "View Details";
        }
    }
</script>
</head>
<body style="font-family:'Poppins',sans-serif; background:#fff; margin:0;">

<h2 style="text-align:center; margin:30px 0; color:#ff5e8e;">Our Services</h2>

<div style="display:flex; flex-wrap:wrap; justify-content:center; gap:30px; padding:20px;">
<?php 
// Loop through services and display them
while($row = mysqli_fetch_assoc($result)) { 
    $service_type = $row['service_type'];  // Get the service type
    $price = $row['price']; // Get the service price
    $description = $row['description']; // Get the service description
    
    // Set the service label and features based on service type
    if ($service_type == 'Premium') {
        $service_label = "Premium Service";
        $label_class = "Premium";
    } elseif ($service_type == 'Medium') {
        $service_label = "Medium Service";
        $label_class = "medium";
        $features = "A great value option with quality service!";
    } else {
        $service_label = "Normal Service";
        $label_class = "normal";
        $features = "";  // No special features for normal
    }
?>
    <div style="width:280px; background:#fff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; padding:20px;">
        <img src="uploads/<?= htmlspecialchars($row['image']); ?>" 
             alt="<?= htmlspecialchars($row['service_name']); ?>" 
             style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
        
        <!-- Display Service Name -->
        <h3 style="color:#333; margin:15px 0 10px;"><?= htmlspecialchars($row['service_name']); ?></h3>
        
        <!-- Display Service Type Label -->
        <p style="color:#ff5e8e; font-weight:bold;"><?= $service_label; ?></p>
        
        <!-- Display Service Description -->
        <p style="color:#777; font-size:14px;"><?= substr($description, 0, 100); ?>...</p>
        
        <!-- 'View Details' Button for Normal Service -->
        <?php if ($service_type == 'Normal') { ?>
            <a href="javascript:void(0);" id="view-details-btn-<?= $row['id']; ?>" 
               onclick="toggleDescription(<?= $row['id']; ?>)"
               style="color:#ff5e8e; text-decoration:none; font-weight:bold;">
               View Details
            </a>
            <span id="more-<?= $row['id']; ?>" style="display:none;">
                <p style="color:#777; font-size:14px; margin-top:10px;"><?= htmlspecialchars($description); ?></p>
            </span>
        <?php } else { ?>
            <!-- For Premium and Medium, show the features -->
            <p style="color:#777; font-size:14px; margin-top:10px;"><?= $features; ?></p>
        <?php } ?>
        
        <!-- Display Price -->
        <strong style="color:#ff5e8e;">Rs. <?= number_format($price, 2); ?></strong><br><br>
        
        <!-- Book Now Button -->
        <a href="booking.php?service=<?= $row['id']; ?>" 
           style="display:inline-block; background:#ff5e8e; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Book Now
        </a>
    </div>
<?php } ?>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
