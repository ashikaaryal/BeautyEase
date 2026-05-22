<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['booking_id'], $_SESSION['amount'])) {
    header("Location: book_appointment.php");
    exit();
}

$booking_id = (int) $_SESSION['booking_id'];

$amount = number_format((float)$_SESSION['amount'], 2, '.', '');
$tax_amount = number_format(0, 2, '.', '');
$total_amount = number_format(((float)$amount + (float)$tax_amount), 2, '.', '');

$transaction_uuid = $booking_id . '-' . time();
$product_code = "EPAYTEST";

$_SESSION['transaction_uuid'] = $transaction_uuid;

$secret_key = "8gBm/:&EnhH.1/q";

// Signature must match signed_field_names order
$signature_string = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$product_code}";
$signature = base64_encode(hash_hmac('sha256', $signature_string, $secret_key, true));

$base_url = "http://localhost/myproject/beautyease";
$success_url = $base_url . "/Payment/payment_success.php";
$failure_url = $base_url . "/Payment/payment_failed.php?booking_id=" . $booking_id;
$stmt = $conn->prepare("INSERT INTO payments 
(booking_id, transaction_uuid, amount, payment_method, payment_status, created_at) 
VALUES (?, ?, ?, ?, ?, NOW())");

$payment_method = "eSewa";
$payment_status = "Paid";

$stmt->bind_param(
    "isdss",
    $booking_id,
    $transaction_uuid,
    $total_amount,
    $payment_method,
    $payment_status
);

$stmt->execute();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment | BeautiEase</title>
<style>
body{font-family:Poppins;background:linear-gradient(to right,#d5b1bc,#ff5e8e);display:flex;justify-content:center;align-items:center;min-height:100vh;}
.container{background:#fff;padding:30px;width:400px;border-radius:20px;text-align:center;}
button{width:100%;padding:14px;border:none;border-radius:10px;font-size:16px;cursor:pointer;margin-top:10px;}
.esewa{background:#ff8200;color:#fff;}
</style>
</head>
<body>
<div class="container">
<h2>Complete Payment</h2>

<p><strong>Booking ID:</strong> #<?= htmlspecialchars($booking_id) ?></p>
<p><strong>Total Amount:</strong> Rs. <?= htmlspecialchars($total_amount) ?></p>

<form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
  <input type="hidden" name="amount" value="<?= $amount ?>">
  <input type="hidden" name="tax_amount" value="<?= $tax_amount ?>">
  <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
  <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
  <input type="hidden" name="product_code" value="<?= $product_code ?>">
  <input type="hidden" name="product_service_charge" value="0">
  <input type="hidden" name="product_delivery_charge" value="0">
  <input type="hidden" name="success_url" value="<?= htmlspecialchars($success_url) ?>">
  <input type="hidden" name="failure_url" value="<?= htmlspecialchars($failure_url) ?>">

  <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
  <input type="hidden" name="signature" value="<?= $signature ?>">

  <button type="submit" class="esewa">Pay with eSewa</button>
   <!-- <button type="submit" class="esewa">cash</button> -->
</form>
</div>
</body>
</html>
