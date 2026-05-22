<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_login('Admin');
include('../includes/connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Booking declined successfully');
                window.location.href='manage_appointments.php';
              </script>";
    } else {
        echo "Error deleting record.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>