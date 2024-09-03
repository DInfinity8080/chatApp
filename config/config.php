<?php

// Email configuration settings
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

$CFG = new stdClass();

// Replace the following URL with your site's URL
$CFG->base_url = 'https://comp3340.gregory2.myweb.cs.uwindsor.ca/';

// Site-wide password salt (Ensure you set a secure, random salt)
$CFG->site_wide_password_salt = 'e3a1b6c8d74e4fa2a9f5e7c6b5a4f3d2e1b0c9a8b7a6d5c4f3e2d1c0b1a2e3f4';

// Set a "global" session timeout (in seconds)
$CFG->session_timeout = 60 * 15; // 15 minutes

// Database information
$CFG->db_dsn = 'mysql:host=localhost;dbname=gregory2_chat_app';
$CFG->db_user = 'gregory2_chat_app';
$CFG->db_pass = 'bus8StEFXngU74tejHrH';

// Email configuration
$CFG->email_config = [
    'host' => 'mail.comp3340.gregory2.myweb.cs.uwindsor.ca',
    'username' => 'contact@comp3340.gregory2.myweb.cs.uwindsor.ca',
    'password' => 'UU6z8ztLj6Zr3ve2m87h',
    'port' => 587,
    'encryption' => PHPMailer::ENCRYPTION_STARTTLS,
    'from_email' => 'contact@comp3340.gregory2.myweb.cs.uwindsor.ca',
    'from_name' => 'Contact Form',
    'to_email' => 'contact@comp3340.gregory2.myweb.cs.uwindsor.ca'
];

// Admin token
$CFG->admin_token = 'securetoken123';


// Database connection function
function connect_db() {
    global $CFG;
    try {
        $conn = new PDO($CFG->db_dsn, $CFG->db_user, $CFG->db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
