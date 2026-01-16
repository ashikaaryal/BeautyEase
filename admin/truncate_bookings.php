<?php
include('../includes/connect.php');
mysqli_query($conn, "TRUNCATE TABLE bookings");
echo "Bookings table truncated.";
?>