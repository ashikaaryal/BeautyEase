<?php
include('includes/connect.php');
include('includes/navbar.php');
 include('tet1.php'); 


 include('about.php'); 

// Fetch all services from the database
$result = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Services - BeautiEase</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body style="font-family:'Poppins',sans-serif; background:#fff; margin:0;">

<h2 style="text-align:center; margin:30px 0; color:#ff5e8e;">Our Services</h2>

<div style="display:flex; flex-wrap:wrap; justify-content:center; gap:30px; padding:20px;">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div style="width:280px; background:#fff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; padding:20px;">
        <img src="uploads/<?= htmlspecialchars($row['image']); ?>" 
             alt="<?= htmlspecialchars($row['service_name']); ?>" 
             style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
        <h3 style="color:#333; margin:15px 0 10px;"><?= htmlspecialchars($row['service_name']); ?></h3>
       
        <a href="services.php?service=<?= $row['id']; ?>" 
           style="display:inline-block; background:#ff5e8e; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none;">view Details</a>
    </div>
<?php } ?>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>

</html>
