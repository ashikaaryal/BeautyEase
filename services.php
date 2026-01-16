<?php 
include('includes/connect.php'); 
include('includes/navbar.php');  

// Fetch all services
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

    if (moreText.style.display === "none") {
        moreText.style.display = "block";
        btnText.innerHTML = "Hide Details";
    } else {
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
while($row = mysqli_fetch_assoc($result)) {

    $service_type = $row['service_type'];
    $price = $row['price'];
    $description = $row['description'];

    // Labels for service cards
    if ($service_type == 'Premium') {
        $service_label = "Premium Service";
        $features = "Exclusive pampering, priority booking, and premium care.";
    } elseif ($service_type == 'Medium') {
        $service_label = "Medium Service";
        $features = "Great value option with quality services.";
    } else {
        $service_label = "Normal Service";
        $features = "";
    }
?>
    
    <div style="width:280px; background:#fff; border-radius:12px; 
                box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; padding:20px;">

        <!-- Service Image -->
        <img src="uploads/<?= htmlspecialchars($row['image']); ?>"
             alt="<?= htmlspecialchars($row['service_name']); ?>"
             style="width:100%; height:180px; object-fit:cover; border-radius:10px;">


        <!-- Service Name -->
        <h3 style="color:#333; margin:15px 0 10px;">
            <?= htmlspecialchars($row['service_name']); ?>
        </h3>

        <!-- Service Type -->
        <p style="color:#ff5e8e; font-weight:bold;">
            <?= $service_label; ?>
        </p>

        <!-- Short Description Preview -->
        <p style="color:#777; font-size:14px;">
            <?= substr($description, 0, 100); ?>...
        </p>

        <!-- View Details Button (For ALL services now) -->
        <a href="javascript:void(0);" 
           id="view-details-btn-<?= $row['id']; ?>"
           onclick="toggleDescription(<?= $row['id']; ?>)"
           style="color:#ff5e8e; text-decoration:none; font-weight:bold;">
           View Details
        </a>

        <!-- Hidden Full Description + extra features -->
        <div id="more-<?= $row['id']; ?>" style="display:none; margin-top:10px;">
            <p style="color:#777; font-size:14px;">
                <?= htmlspecialchars($description); ?>
            </p>

            <?php if ($features != "") { ?>
                <p style="color:#555; font-size:14px;">
                    <strong>Extra Features:</strong> <?= $features; ?>
                </p>
            <?php } ?>
        </div>

        <!-- Price -->
        <strong style="color:#ff5e8e;">Rs. <?= number_format($price, 2); ?></strong><br><br>

        <!-- Book Now Button -->
        <a href="booking.php?service=<?= $row['id']; ?>"
           style="display:inline-block; background:#ff5e8e; color:#fff; 
                  padding:10px 20px; border-radius:8px; text-decoration:none;">
            Book Now
        </a>

    </div>

<?php } ?>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
