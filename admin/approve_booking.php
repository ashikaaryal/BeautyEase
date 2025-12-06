<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../admin/PHPMailer/src/Exception.php';
require '../admin/PHPMailer/src/PHPMailer.php';
require '../admin/PHPMailer/src/SMTP.php';
include('../includes/connect.php');

if (isset($_GET['id']) && isset($_GET['email']) && isset($_GET['service'])) {

    $id = $_GET['id'];
    $email = $_GET['email'];
    $service = $_GET['service'];

    // ✅ Correct status name (must match UI: Pending → Approved)
    $update = "UPDATE bookings SET status='Approved' WHERE id='$id'";
    mysqli_query($conn, $update);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {

        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // 🔸 Your Gmail + App Password
        $mail->Username = 'ashikaaryal560@gmail.com';
        $mail->Password = 'uzcg wzmu offu eimy';  // App password ONLY

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender & Receiver
        $mail->setFrom('ashikaaryal560@gmail.com', 'BeautiEase Admin');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Appointment Has Been Approved!';
        $mail->Body = "
            <h3>Hello,</h3>
            <p>Your booking for <strong>$service</strong> has been <span style='color:green; font-weight:bold;'>Approved</span> ✔</p>
            <p>We look forward to serving you soon 💅</p>
            <br>
            <p>— BeautiEase Team</p>
        ";

        // Send email
        $mail->send();

        // Redirect back
        echo "<script>
            alert('Appointment approved & email sent!');
            window.location.href='manage_appointments.php';
        </script>";

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    echo "Invalid request.";
}
?>
