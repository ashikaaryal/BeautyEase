<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_login('Admin');
include('../includes/connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete = "DELETE FROM bookings WHERE id = '$id'";
    if (mysqli_query($conn, $delete)) {
        echo "<script>alert('Booking declined successfully'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
