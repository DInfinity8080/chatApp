<?php
$pageTitle = 'Contact Us';
include 'header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

// Load configuration
include 'common.php';

// Email configuration
$email_config = $CFG->email_config;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clean and verify the form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = trim($_POST['message']);  // Allow HTML content

    // Define the email subject
    $subject = "New Contact Us Message from $name";

    // Create the email content
    $email_content = "<p><strong>Name:</strong> $name</p>";
    $email_content .= "<p><strong>Email:</strong> $email</p>";
    $email_content .= "<p><strong>Message:</strong><br>" . nl2br($message) . "</p>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $email_config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $email_config['username'];
        $mail->Password = $email_config['password'];
        $mail->SMTPSecure = $email_config['encryption'];
        $mail->Port = $email_config['port'];

        // Recipients
        $mail->setFrom($email_config['from_email'], $email_config['from_name']);
        $mail->addAddress($email_config['to_email']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $email_content;

        $mail->send();
        $_SESSION['message'] = "Thank you for contacting us! We'll get back to you shortly.";
    } catch (Exception $e) {
        $_SESSION['message'] = "Oops! Something went wrong and we couldn't send your message. Mailer Error: " . $mail->ErrorInfo;
    }

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/contact_us.css">
    <!-- Include local TinyMCE -->
    <script src="js/tinymce/tinymce.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="4" cols="25" placeholder="Enter your message here..."></textarea>
                </div>
                <div class="input-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        tinymce.init({
            selector: '#message',
            height: 500,
            menubar: false,
            toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                     'alignleft aligncenter alignright alignjustify | ' +
                     'bullist numlist outdent indent | removeformat | help',
            license_key: 'gpl'
        });
    </script>
</body>
</html>
