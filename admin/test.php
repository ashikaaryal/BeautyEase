<?php
include('../includes/connect.php');
session_start();

if (isset($_POST['action']) && isset($_POST['selected_appointments'])) {
    $action = $_POST['action'];
    $appointments = $_POST['selected_appointments'];

    // Start processing based on action (Approve or Complete)
    foreach ($appointments as $appointment_id) {
        if ($action == 'approve') {
            // Update status to "Approved"
            $query = "UPDATE bookings SET status = 'Approved' WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $appointment_id);
            $stmt->execute();
        } elseif ($action == 'complete') {
            // Update status to "Completed"
            $query = "UPDATE bookings SET status = 'Completed' WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $appointment_id);
            $stmt->execute();
        }
    }

    // Redirect back with success message
    header("Location: manage_appointments.php?status=success");
    exit;
} else {
    // Redirect back if no action or appointments selected
    header("Location: manage_appointments.php?status=error");
    exit;
}
?>
