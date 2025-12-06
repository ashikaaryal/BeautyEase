<?php
session_start();

include('../includes/connect.php');


// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if booking data exists in session
if (!isset($_SESSION['booking_id']) || !isset($_SESSION['service_price'])) {
    header("Location: book_appointment.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];
$amount = $_SESSION['service_price'];
$amount_in_paisa = $amount * 100; // Convert to paisa for Khalti

// Fetch service details
$service_id = $_SESSION['service_id'];
$service_query = mysqli_query($conn, "SELECT * FROM services WHERE id = '$service_id'");

if (!$service_query) {
    die("Database query failed: " . mysqli_error($conn));
}

$service = mysqli_fetch_assoc($service_query);

if (!$service) {
    die("Service not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | BeautiEase</title>
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
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
            color: #ff5e8e;
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

        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #ff5e8e;
            margin: 20px 0;
        }

        #payment-button {
            background-color: #7732d9;
            border: none;
            border-radius: 10px;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        #payment-button:hover {
            background-color: #5a24a5;
            transform: scale(1.05);
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            color: #ff5e8e;
            text-decoration: none;
            font-weight: 600;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment 💳</h2>
        
        <!-- <div class="booking-details">
            <p><strong>Booking ID:</strong> #<?php echo $booking_id; ?></p>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($service['service_name']); ?></p>
            <p><strong>Date:</strong> <?php echo $_SESSION['date']; ?></p>
            <p><strong>Time:</strong> <?php echo $_SESSION['time']; ?></p>
            <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        </div> -->

        <div class="amount">
            Total Amount: Rs. <?php echo number_format($amount, 2); ?>
        </div>

        <button id="payment-button">Pay with Khalti</button>
          <button id="payment-button">Pay with Cash</button>
        <br>
        <a href="book_appointment.php" class="back-btn">← Back to Booking</a>
    </div>

    <script>
      var config = {
    "publicKey": "01eeaeea218b43268a1d21f5c0cd2ace", 
    "productIdentity": "<?php echo $booking_id; ?>",
    "productName": "<?php echo htmlspecialchars($service['service_name']); ?>",
    "productUrl": "http://localhost/myproject/beautyease",
    "eventHandler": {
        onSuccess (payload) {
            console.log(payload);
            // Verify payment on server
            verifyPayment(payload);
        },
        onError (error) {
            console.log(error);
            alert('Payment failed: ' + error.message);
        },
        onClose () {
            console.log('Widget is closing');
        }
    }
};

        var checkout = new KhaltiCheckout(config);

        var button = document.getElementById("payment-button");
        button.onclick = function () {
            checkout.show({amount: <?php echo $amount_in_paisa; ?>});
        }

        function verifyPayment(payload) {
            fetch('verify_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    token: payload.token,
                    amount: <?php echo $amount_in_paisa; ?>,
                    booking_id: <?php echo $booking_id; ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment successful! Your booking is confirmed.');
                    // Redirect to success page
                    window.location.href = 'payment_success.php?booking_id=<?php echo $booking_id; ?>';
                } else {
                    alert('Payment verification failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Verification error occurred');
            });
        }
    </script>
</body>
</html>