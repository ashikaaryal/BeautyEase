<?php
session_start();
include('includes/connect.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch bookings + service names
$sql = "SELECT b.*, s.service_name 
        FROM bookings b 
        JOIN services s ON b.service_id = s.id
        WHERE b.email = '$email'
        ORDER BY b.date";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
  
<style>
/* Background */
body {
    font-family: Arial, sans-serif;
    background: #a81c5bff;
    margin: 0;
    padding: 0;
}

/* Container */
.booking-container {
    width: 80%;
    margin: 50px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

/* Title */
.booking-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #a81c5bff;
    font-size: 28px;
    font-weight: bold;
}

/* Table */
.booking-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.booking-table th {
    background: #ff4d79;
    color: white;
    padding: 12px 10px;
    text-align: center;
    font-size: 16px;
}

.booking-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

.booking-table tr:nth-child(even) {
    background: #fafafa;
}

.booking-table tr:hover {
    background: #ffe6ee;
}

/* Status styling */
.status {
    font-weight: bold;
    padding: 7px 12px;
    border-radius: 6px;
    display: inline-block;
}

/* Status Colors */
.status.pending {
    background: #ffeb99;
    color: #c28b00;
}

.status.approved {
    background: #c0ffc0;
    color: #008000;
}

.status.completed {
    background: #9cffb3;
    color: #006622;
}

.status.rejected {
    background: #ffb3b3;
    color: #b30000;
}

.no-data {
    text-align: center;
    padding: 20px;
    font-size: 18px;
}
</style>
</head>

<body>

<div class="booking-container">
    <h2>My Bookings</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="booking-table">
            <tr>
                <th>Email</th>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>

                    <!-- Show service name, NOT ID -->
                    <td><?php echo $row['service_name']; ?></td>

                    <td><?php echo $row['date']; ?></td>

                    <!-- Status with color -->
                    <td class="status <?php echo strtolower($row['status']); ?>">
                        <?php echo $row['status']; ?>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    <?php else: ?>
        <p class="no-data">No bookings found.</p>
    <?php endif; ?>
</div>

</body>
</html>
