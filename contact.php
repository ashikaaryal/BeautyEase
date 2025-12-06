<?php
include('includes/connect.php');
include('includes/navbar.php');

$admin_email = "admin@beautyease.com";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Save to database
    $sql = "INSERT INTO contact_messages (name,email,message) VALUES ('$name','$email','$message')";
    if (mysqli_query($conn, $sql)) {
        // Send email to admin
        $subject = "New Contact Message from $name";
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        if(mail($admin_email, $subject, $body, $headers)) {
            $success = "Thank you! Your message has been sent.";
        } else {
            $success = "Message saved, but email could not be sent.";
        }
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - BeautiEase</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h2 class="title">Contact Us</h2>
<div class="contact-container">
 <!-- <div class="contact-info">
        <h3>Our Information</h3>
        <p><strong>Address:</strong> 123 Beauty Street, Kathmandu, Nepal</p>
        <p><strong>Phone:</strong> +977 9800000000</p>
        <p><strong>Email:</strong> info@beautyease.com</p>
        <p><strong>Follow Us:</strong> Facebook | Instagram | Twitter</p>
    </div>  -->

    <div class="contact-form">
        <h3>Send a Message</h3>
        <?php if(isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
            <button type="submit" name="submit">Send Message</button>
        </form>
    </div>

</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
