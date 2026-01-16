<?php
session_start();
include('../includes/connect.php');

if (!isset($_GET['data'])) {
    die("Invalid Request");
}

// Decode response
$response = json_decode(base64_decode($_GET['data']), true);

$transaction_uuid = $response['transaction_uuid'];
$status = $response['status'];
$total_amount = $response['total_amount'];

if ($status !== "COMPLETE") {
    die("Payment not completed");
}

// Verify transaction UUID
if ($transaction_uuid !== $_SESSION['transaction_uuid']) {
    die("Transaction mismatch");
}

// OPTIONAL: Verify with eSewa server (recommended)
$url = "https://rc-epay.esewa.com.np/api/epay/transaction/status/?product_code=EPAYTEST&transaction_uuid={$transaction_uuid}&total_amount={$total_amount}";
$response = file_get_contents($url);
$result = json_decode($response, true);

if ($result['status'] === "COMPLETE") {

    $booking_id = $_SESSION['booking_id'];

    mysqli_query($conn, "
        UPDATE bookings 
        SET payment_status='Paid', payment_method='eSewa' 
        WHERE id='$booking_id'
    ");

    header("Location: payment_success.php?booking_id=$booking_id");
    exit();
}

die("Payment verification failed");
