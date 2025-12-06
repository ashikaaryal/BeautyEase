<?php
include('includes/connect.php');
session_start();

// Initialize variables
$service_name = '';
$service_price = '';
$message = '';
$msg_class = '';

if (isset($_POST['book'])) {
    $address_type = $_POST['address_type'];

    // Only get address if Home Service
    $address = ($address_type === "Home Service") ? $_POST['address'] : '';

    $email = $_POST['email'];
    $phone = $_POST['phone']; 
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Check slot availability
    $check = mysqli_query($conn, "SELECT * FROM bookings 
        WHERE date='$date' AND time='$time' AND service_id='$service_id'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('Sorry, this slot is already booked. Please choose another time.');
                window.history.back();
              </script>";
        exit(); 
    } else {
        // Insert booking
        $sql = "INSERT INTO bookings (address_type, address, service_id, date, time, email, phone) 
                VALUES ('$address_type', '$address', '$service_id', '$date', '$time', '$email', '$phone')";

        if (mysqli_query($conn, $sql)) {
            $booking_id = mysqli_insert_id($conn);
            
            $_SESSION['booking_id'] = $booking_id;
            $_SESSION['service_id'] = $service_id;
            $_SESSION['amount'] = $service_price;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['date'] = $date;
            $_SESSION['time'] = $time;

            header("Location: Payment/payment.php");
            exit();
        } else {
            $message = "Error: Could not complete booking.";
            $msg_class = "error";
        }
    }
}

// Fetch service details
if (isset($_GET['service'])) {
    $service_id = $_GET['service'];
    $service_query = mysqli_query($conn, "SELECT * FROM services WHERE id = '$service_id'");
    $service = mysqli_fetch_assoc($service_query);

    if ($service) {
        $service_name = $service['service_name'];
        $service_price = $service['price'];
        $_SESSION['service_price'] = $service_price;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment | BeautiEase</title>

<!-- INLINE CSS -->
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(to right, #d5b1bcff, #ff5e8e);
  display:flex; justify-content:center; align-items:center;
  min-height:100vh; margin:0;
}
.container {
  background:#fff; width:90%; max-width:500px;
  padding:35px; border-radius:20px;
  box-shadow:0 8px 25px rgba(0,0,0,0.1);
}
h2 { color:#ff5e8e; text-align:center; margin-bottom:20px; }
label { margin-top:15px; font-weight:600; display:block; }
input, select, textarea {
  width:100%; padding:12px; border-radius:10px;
  border:1px solid #ccc; font-size:15px; margin-top:5px;
}
.btn {
  width:100%; padding:12px; margin-top:25px;
  background:#ff5e8e; border:none; border-radius:10px;
  color:#fff; font-weight:600; cursor:pointer;
}
.btn:hover { background:#e0547b; }
.hidden { display:none; }
textarea { resize:none; height:80px; }
.back {
  display:block; margin-top:20px; text-align:center;
  color:#ff5e8e; text-decoration:none; font-weight:600;
}
.message { padding:10px; border-radius:8px; margin-bottom:10px; text-align:center; }
.error { background:#f8d7da; color:#721c24; }
.success { background:#d4edda; color:#155724; }
</style>

</head>

<body>

<div class="container">
<h2>Book Your Appointment 💅</h2>

<?php if (!empty($message)): ?>
  <div class="message <?= $msg_class; ?>"><?= $message; ?></div>
<?php endif; ?>

<form method="POST">

  <label>Service Selected:</label>
  <input type="text" value="<?= htmlspecialchars($service_name); ?>" readonly>

  <label>Price:</label>
  <input type="text" value="Rs. <?= number_format($service_price,2); ?>" readonly>

  <input type="hidden" name="service_id" value="<?= $service_id; ?>">

  <!-- ADDRESS TYPE -->
  <label>Choose Location</label>
  <select name="address_type" id="address_type" required>
    <option value="">Select Option</option>
    <option value="Home Service">Home Service</option>
    <option value="Office Location">Office Location</option>
  </select>

  <!-- ADDRESS FIELD ONLY FOR HOME -->
  <div id="address_box" class="hidden">
    <label>Enter Address</label>
    <textarea name="address" placeholder="Enter address here"></textarea>
  </div>

  <label>Select Date</label>
  <input type="date" name="date" required min="<?= date('Y-m-d'); ?>">

  <label>Select Time</label>
  <input type="time" name="time" required>

  <label>Email</label>
  <input type="email" name="email" placeholder="Enter your valid email" required>

  <label>Phone Number</label>
  <input type="text" name="phone" placeholder="Enter phone number" required>

  <button type="submit" name="book" class="btn">Confirm Booking & Proceed to Payment</button>
</form>

<a href="index.php" class="back">← Back to Home</a>

</div>

<script>
// Show address only for Home Service
document.getElementById("address_type").addEventListener("change", function () {
    let box = document.getElementById("address_box");
    if (this.value === "Home Service") {
        box.classList.remove("hidden");
    } else {
        box.classList.add("hidden"); // Hide for Office Location
    }
});
</script>

</body>
</html>
