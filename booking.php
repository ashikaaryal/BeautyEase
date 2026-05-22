<?php
include('includes/connect.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$service_name = '';
$service_price = 0;
$message = '';
$msg_class = '';

if (!isset($_GET['service']) || !is_numeric($_GET['service'])) {
    header("Location: services.php");
    exit();
}

$service_id = intval($_GET['service']);
$query = mysqli_prepare($conn, "SELECT service_name, price FROM services WHERE id = ?");
mysqli_stmt_bind_param($query, "i", $service_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$service = mysqli_fetch_assoc($result);

if ($service) {
    $service_name = $service['service_name'];
    $service_price = $service['price'];
} else {
    header("Location: services.php");
    exit();
}

if (isset($_POST['book'])) {
    $email = $_SESSION['email'];
    $service_id = intval($_POST['service_id']);
    $address_type = $_POST['address_type'];
    $address = ($address_type == "Home Service") ? trim($_POST['address']) : '';
    $date = $_POST['date'];
    $time = $_POST['time'];
    $phone = trim($_POST['phone']);
    $service_price= $service['price'];

    // Validation
    if (empty($date) || strtotime($date) < strtotime('today')) {
        echo "<script>alert('Please select a valid future date.');</script>";
        exit();
    }

    if (empty($time)) {
        echo "<script>alert('Please select a time.');</script>";
        exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Phone number must be exactly 10 digits.');</script>";
        exit();
    }

    if ($address_type == "Home Service" && empty($address)) {
        echo "<script>alert('Please provide an address for home service.');</script>";
        exit();
    }

    // Check if slot is available
    $stmt = mysqli_prepare($conn, "SELECT id FROM bookings WHERE service_id = ? AND `date` = ? AND `time` = ?");
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "iss", $service_id, $date, $time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        echo "<script>alert('Slot already booked.'); history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Insert booking
    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (service_id, address_type, address, price, `date`, `time`, email, phone, status) VALUES (?,?, ?, ?, ?, ?, ?, ?, 'Pending')");
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "isssssss", $service_id, $address_type, $address,  $service_price, $date, $time, $email, $phone);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['booking_id'] = mysqli_insert_id($conn);
        $_SESSION['amount'] = $service_price;
        mysqli_stmt_close($stmt);
        header("Location: Payment/payment.php");
        exit();
    } else {
        $message = "Booking failed";
        $msg_class = "error";
    }
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment</title>

<style>
body{
  font-family:Poppins;
  background:linear-gradient(to right,#d5b1bc,#ff5e8e);
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
  margin:0;
}
.container{
  background:#fff;
  width:90%;
  max-width:500px;
  padding:30px;
  border-radius:20px;
}
h2{
  color:#ff5e8e;
  text-align:center;
}
label{
  display:block;
  font-weight:600;
  margin-top:15px;
}
input,select,textarea{
  width:100%;
  padding:12px;
  margin-top:5px;
  border-radius:10px;
  border:1px solid #ccc;
}
textarea{
  resize:none;
  height:80px;
}
.btn{
  width:100%;
  margin-top:25px;
  background:#ff5e8e;
  color:#fff;
  padding:12px;
  border:none;
  border-radius:10px;
  font-weight:600;
}
.hidden{
  display:none;
}
.error{
  background:#f8d7da;
  color:#721c24;
  padding:10px;
  border-radius:8px;
  margin-bottom:10px;
}
</style>
</head>

<body>

<div class="container">
<h2>Book Your Appointment 💅</h2>

<?php if ($message): ?>
<div class="<?= $msg_class ?>"><?= $message ?></div>
<?php endif; ?>

<form method="POST">

<label>Service</label>
<input type="text" value="<?= htmlspecialchars($service_name) ?>" readonly>

<label>Price</label>
<input type="text" value="Rs. <?= number_format($service_price,2) ?>" readonly>

<input type="hidden" name="service_id" value="<?= $service_id ?>">

<label>Choose Location</label>
<select name="address_type" id="address_type" required>
  <option value="">Select</option>
  <option value="Home Service">Home Service</option>
  <option value="Office Location">Office Location</option>
</select>

<div id="address_box" class="hidden">
<label>Address</label>
<textarea name="address"></textarea>
</div>

<label>Date</label>
<input type="date" name="date" min="<?= date('Y-m-d') ?>" required>

<label>Time</label>
<input type="time" name="time" required>

<label>Phone</label>
<input type="text" name="phone" required>

<button class="btn" type="submit" name="book">Confirm Booking</button>



</form>
</div>

<script>
document.getElementById("address_type").addEventListener("change",function(){
  document.getElementById("address_box").classList.toggle(
    "hidden", this.value !== "Home Service"
  );
});
</script>

</body>
</html>
