<?php
include('../includes/connect.php');

$booking_id = $_GET['booking_id'] ?? null;
$msg = $_GET['msg'] ?? 'Payment failed or was cancelled.';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | BeautiEase</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fff4f6; display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0; }
        .box { background:#fff;padding:30px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.08);max-width:600px;text-align:center; }
        .btn { background:#ff5e8e;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;margin-top:18px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Payment Failed</h2>
        <p><?php echo htmlspecialchars($msg); ?></p>
        <?php if ($booking_id): ?>
            <a class="btn" href="payment.php">Retry Payment</a>
        <?php else: ?>
            <a class="btn" href="../index.php">Back to Home</a>
        <?php endif; ?>
    </div>
</body>
</html>
