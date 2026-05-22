<?php
session_start();
include('../includes/connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Check if required fields exist
    if (isset($_POST['booking_id']) && isset($_POST['amount'])) {
        
        $booking_id = (int) $_POST['booking_id'];
        $amount = (float) $_POST['amount'];
        $transaction_uuid = isset($_POST['transaction_uuid']) ? $_POST['transaction_uuid'] : $booking_id . '-cash-' . time();
        
        // First, check if a cash payment already exists for this booking
        $check_sql = "SELECT id FROM cash_payments WHERE booking_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $booking_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update existing cash payment
            $update_sql = "UPDATE cash_payments SET amount = ?, status = 'cashPaid', payment_date = NOW() WHERE booking_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("di", $amount, $booking_id);
            
            if ($update_stmt->execute()) {
                // Store success message in session
                $_SESSION['payment_success'] = "Cash payment updated successfully!";
                $_SESSION['payment_method'] = 'cash';
                $_SESSION['payment_amount'] = $amount;
                
                // Clear online payment session data
                unset($_SESSION['transaction_uuid']);
                
                header("Location: payment_success.php?type=cash&booking_id=" . $booking_id);
                exit();
            } else {
                $_SESSION['payment_error'] = "Failed to update cash payment: " . $update_stmt->error;
                header("Location: payment_failed.php?booking_id=" . $booking_id . "&error=update_failed");
                exit();
            }
            $update_stmt->close();
        } else {
            // Insert new cash payment
            $insert_sql = "INSERT INTO cash_payments (booking_id, amount, status, payment_date) VALUES (?, ?, 'cashPaid', NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("id", $booking_id, $amount);
            
            if ($insert_stmt->execute()) {
                // Store success message in session
                $_SESSION['payment_success'] = "Cash payment recorded successfully!";
                $_SESSION['payment_method'] = 'cash';
                $_SESSION['payment_amount'] = $amount;
                
                // Clear online payment session data
                unset($_SESSION['transaction_uuid']);
                
                header("Location: payment_success.php?type=cash&booking_id=" . $booking_id);
                exit();
            } else {
                $_SESSION['payment_error'] = "Failed to record cash payment: " . $insert_stmt->error;
                header("Location: payment_failed.php?booking_id=" . $booking_id . "&error=insert_failed");
                exit();
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
        
    } else {
        // Missing required fields
        $missing = array();
        if (!isset($_POST['booking_id'])) $missing[] = 'booking_id';
        if (!isset($_POST['amount'])) $missing[] = 'amount';
        
        $error_msg = "Missing data: " . implode(', ', $missing);
        $_SESSION['payment_error'] = $error_msg;
        header("Location: payment_failed.php?error=missing_data&fields=" . implode(',', $missing));
        exit();
    }
} else {
    // Not a POST request
    header("Location: book_appointment.php");
    exit();
}
?>