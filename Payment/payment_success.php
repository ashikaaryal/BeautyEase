<?php
include('../includes/connect.php');

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    die("Booking ID not provided.");
}

$booking_id = $_GET['booking_id'];

// Fetch booking details
$booking_query = mysqli_query($conn, "SELECT b.*, s.service_name, s.price 
                                      FROM bookings b 
                                      JOIN services s ON b.service_id = s.id 
                                      WHERE b.id = '$booking_id'");

if (!$booking_query) {
    die("Database query failed: " . mysqli_error($conn));
}

$booking = mysqli_fetch_assoc($booking_query);

if (!$booking) {
    die("Booking not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | BeautiEase</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d5b1bcff, #ff5e8e);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            width: 90%;
            max-width: 500px;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .booking-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: left;
        }

        .booking-details p {
            margin: 8px 0;
            font-size: 14px;
        }

        .success-icon {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #4CAF50;
            border: none;
            border-radius: 10px;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h2>Payment Successful!</h2>
        
        <div class="booking-details">
            <p><strong>Booking ID:</strong> #<?php echo $booking['id']; ?></p>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($booking['service_name']); ?></p>
            <p><strong>Date:</strong> <?php echo $booking['date']; ?></p>
            <p><strong>Time:</strong> <?php echo $booking['time']; ?></p>
            <p><strong>Email:</strong> <?php echo $booking['email']; ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($booking['address']); ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?php echo number_format($booking['price'], 2); ?></p>
        </div>

        <p>Thank you for your booking! We will contact you soon.</p>
        <a href="../index.php" class="btn">Back to Home</a>
    </div>
</body>
</html>