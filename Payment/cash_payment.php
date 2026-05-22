<?php
session_start();
include('../includes/connect.php');

if (!isset($_GET['booking_id'])) {
    die("Booking ID is missing.");
}

$booking_id = intval($_GET['booking_id']);

if (!isset($_SESSION['amount'])) {
    die("Amount not found in session.");
}

$amount = $_SESSION['amount'];

if (isset($_POST['submit'])) {
    // Insert into cash_payments
    $stmt = $conn->prepare("INSERT INTO cash_payments (booking_id, amount, status) VALUES (?, ?, 'cashPaid')");
    $stmt->bind_param("id", $booking_id, $amount);

    if ($stmt->execute()) {
        // Optional: update booking status
        $update = $conn->prepare("UPDATE bookings SET status='Approved' WHERE id=?");
        $update->bind_param("i", $booking_id);
        $update->execute();
        $update->close();

        // Redirect to success page
        header("Location: success.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cash Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-card {
            background: #ffffff;
            width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
        }

        .payment-card h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background: #f8f9fc;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4e73df;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #2e59d9;
        }
    </style>
</head>

<body>

<div class="payment-card">
    <h2>💵 Cash Payment</h2>

    <form method="POST">

        <div class="form-group">
            <label>Booking ID</label>
            <input type="text" value="<?php echo $booking_id; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Amount (Rs.)</label>
            <input type="text" value="<?php echo $_SESSION['amount']; ?>" readonly>

        </div>

        <button type="submit" name="submit" class="btn">
            Confirm Payment
        </button>

    </form>
</div>

</body>
</html>