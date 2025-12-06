<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../admin/PHPMailer/src/Exception.php';
require '../admin/PHPMailer/src/PHPMailer.php';
require '../admin/PHPMailer/src/SMTP.php';
include('../includes/connect.php');

if (isset($_GET['id']) && isset($_GET['email']) && isset($_GET['service'])) {
    // Get the appointment details from the URL
    $id = $_GET['id'];
    $email = $_GET['email'];
    $service = $_GET['service'];

    // Update booking status to 'Completed'
    $update = "UPDATE bookings SET status='Completed' WHERE id='$id'";
    if (mysqli_query($conn, $update)) {
        // Email setup using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Set up PHPMailer
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
            $mail->SMTPAuth = true;
   $mail->Username = 'ashikaaryal560@gmail.com';
    $mail->Password = 'uzcg wzmu offu eimy '; // Replace with your app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set email details
            $mail->setFrom('ashikaaryal560@gmail.com', 'BeautiEase Admin');
            $mail->addAddress($email);

            // Set email format to HTML
            $mail->isHTML(true);
            $mail->Subject = 'Your Appointment Has Been Completed!';
            $mail->Body = "
                <h3>Hi there,</h3>
                <p>Your booking for <strong>$service</strong> has been <b>completed</b> 🎉</p>
                <p>Thank you for choosing BeautiEase!</p>
                <p>— The BeautiEase Team 💅</p>
            ";

            // Send email
            if ($mail->send()) {
                echo "<script>alert('Appointment completed and email sent successfully!'); window.location.href='manage_appointments.php';</script>";
            } else {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Failed to update the booking status.";
    }
} else {
    echo "Invalid request.";
}
?>
