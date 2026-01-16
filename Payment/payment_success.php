<?php
session_start();
include('../includes/connect.php');

// Prevent direct access
if (!isset($_SESSION['booking_id'], $_SESSION['transaction_uuid'])) {
    header("Location: ../book_appointment.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];
$transaction_uuid = $_SESSION['transaction_uuid'];
$paid_amount = $_SESSION['amount']; // original amount

// ✅ Update booking table (SAVE PAYMENT)
$sql = "UPDATE payments
        SET payment_status = 'Paid',
            payment_method = 'eSewa',
            transaction_uuid = '$transaction_uuid',
            paid_amount = '$paid_amount'
        WHERE booking_id = '$booking_id'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
}

// Clear payment-related session data
unset($_SESSION['transaction_uuid']);
unset($_SESSION['amount']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>

<style>
body{
    font-family:Poppins;
    background:linear-gradient(to right,#d5b1bc,#ff5e8e);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.box{
    background:#fff;
    padding:35px;
    width:380px;
    border-radius:20px;
    text-align:center;
}
.icon{
    font-size:60px;
    color:#4CAF50;
}
.btn{
    display:block;
    margin-top:20px;
    padding:12px;
    background:#ff5e8e;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}
</style>
</head>

<body>

<div class="box">
    <div class="icon">✔</div>
    <h2>Payment Successful!</h2>
    <p>Your booking has been confirmed.</p>

    <p><strong>Booking ID:</strong> #<?= htmlspecialchars($booking_id) ?></p>
    <p><strong>Amount Paid:</strong> Rs. <?= htmlspecialchars($paid_amount) ?></p>
    <p><strong>Payment Method:</strong> eSewa</p>

    <a href="../user/dashboard.php" class="btn">Go to Dashboard</a>
</div>

</body>
</html>
