<?php
session_start();
include('../includes/connect.php');

if (!isset($_GET['data'])) {
    die("Invalid Request: missing data");
}

$decoded = json_decode(base64_decode($_GET['data']), true);
if (!is_array($decoded)) {
    die("Invalid data payload");
}

$transaction_uuid = $decoded['transaction_uuid'] ?? '';
$status = $decoded['status'] ?? '';
$total_amount = $decoded['total_amount'] ?? '';

if ($status !== "COMPLETE") {
    die("Payment not completed");
}

if (!isset($_SESSION['transaction_uuid']) || $transaction_uuid !== $_SESSION['transaction_uuid']) {
    die("Transaction mismatch");
}

/* ==========================
   VERIFY WITH ESEWA SERVER
========================== */

$product_code = "EPAYTEST";

$url = "https://rc-epay.esewa.com.np/api/epay/transaction/status/?product_code={$product_code}&transaction_uuid={$transaction_uuid}&total_amount={$total_amount}";

$raw = @file_get_contents($url);
if ($raw === false) {
    die("Unable to verify with eSewa server");
}

$result = json_decode($raw, true);
if (!is_array($result) || ($result['status'] ?? '') !== "COMPLETE") {
    die("Payment verification failed");
}

/* ==========================
   STORE / UPDATE DATABASE
========================== */

$booking_id = (int) ($_SESSION['booking_id'] ?? 0);
$payment_method = "eSewa";
$payment_status = "Completed";

/* Check if record exists */
$check = $conn->prepare("SELECT id FROM payments WHERE transaction_uuid = ?");
$check->bind_param("s", $transaction_uuid);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

    // Update existing record
    $stmt = $conn->prepare("UPDATE payments 
        SET payment_status = ?, paid_at = NOW() 
        WHERE transaction_uuid = ?");

    $stmt->bind_param("ss", $payment_status, $transaction_uuid);

} else {

    // Insert new record (if not inserted earlier)
    $stmt = $conn->prepare("INSERT INTO payments 
        (booking_id, transaction_uuid, amount, payment_method, payment_status, paid_at) 
        VALUES (?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param(
        "isdss",
        $booking_id,
        $transaction_uuid,
        $total_amount,
        $payment_method,
        $payment_status
    );
}

$stmt->execute();
$stmt->close();
$check->close();

/* Optional: clear session */
unset($_SESSION['transaction_uuid']);
unset($_SESSION['booking_id']);
unset($_SESSION['amount']);

echo "<h2>Payment Successful!</h2>";
header("Refresh: 3; URL=../index.php");
?>
