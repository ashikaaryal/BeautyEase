<?php
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
?>

<h2>Payment Successful!</h2>
<p>Your payment for booking ID <strong><?= htmlspecialchars($booking_id) ?></strong> has been received.</p>
<p>Thank you for choosing BeautiEase. We look forward to serving you!</p>
<a href="../index.php">Go to Home</a>
