<?php
include('../includes/connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete = "DELETE FROM bookings WHERE id = '$id'";
    if (mysqli_query($conn, $delete)) {
        echo "<script>alert('Booking deleted successfully'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
