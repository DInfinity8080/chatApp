<?php
require_once 'common.php';

if (!isset($_GET['token']) || $_GET['token'] !== $CFG->admin_token) {
    die("Access denied.");
}

// Database connection
$dsn = $CFG->db_dsn;
$user = $CFG->db_user;
$pass = $CFG->db_pass;

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to check if a user already exists
    function userExists($conn, $username) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    // Standardized user data with hashed passwords
    $users = [
        ['username' => 'testuser1', 'bio' => 'This is a test user.', 'created_at' => '2024-07-01 00:00:00', 'password' => password_hash('testpassword1' . $CFG->site_wide_password_salt, PASSWORD_DEFAULT), 'email' => 'testuser1@example.com', 'fullname' => 'Test User 1', 'profile_pic' => 'images/default-profile-pic.jpg', 'ac_status' => 1],
        ['username' => 'testuser2', 'bio' => 'This is another test user.', 'created_at' => '2024-07-01 00:00:00', 'password' => password_hash('testpassword2' . $CFG->site_wide_password_salt, PASSWORD_DEFAULT), 'email' => 'testuser2@example.com', 'fullname' => 'Test User 2', 'profile_pic' => 'images/default-profile-pic.jpg', 'ac_status' => 1],
        ['username' => 'testuser3', 'bio' => 'This is yet another test user.', 'created_at' => '2024-07-01 00:00:00', 'password' => password_hash('testpassword3' . $CFG->site_wide_password_salt, PASSWORD_DEFAULT), 'email' => 'testuser3@example.com', 'fullname' => 'Test User 3', 'profile_pic' => 'images/default-profile-pic.jpg', 'ac_status' => 1],
        ['username' => 'testuser4', 'bio' => 'Test user number four.', 'created_at' => '2024-07-01 00:00:00', 'password' => password_hash('testpassword4' . $CFG->site_wide_password_salt, PASSWORD_DEFAULT), 'email' => 'testuser4@example.com', 'fullname' => 'Test User 4', 'profile_pic' => 'images/default-profile-pic.jpg', 'ac_status' => 1],
        ['username' => 'testuser5', 'bio' => 'The fifth test user.', 'created_at' => '2024-07-01 00:00:00', 'password' => password_hash('testpassword5' . $CFG->site_wide_password_salt, PASSWORD_DEFAULT), 'email' => 'testuser5@example.com', 'fullname' => 'Test User 5', 'profile_pic' => 'images/default-profile-pic.jpg', 'ac_status' => 1],
    ];

    foreach ($users as $user) {
        if (!userExists($conn, $user['username'])) {
            $stmt = $conn->prepare("INSERT INTO users (username, bio, created_at, password, email, fullname, profile_pic, ac_status) VALUES (:username, :bio, :created_at, :password, :email, :fullname, :profile_pic, :ac_status)");
            $stmt->execute([
                'username' => $user['username'],
                'bio' => $user['bio'],
                'created_at' => $user['created_at'],
                'password' => $user['password'],
                'email' => $user['email'],
                'fullname' => $user['fullname'],
                'profile_pic' => $user['profile_pic'],
                'ac_status' => $user['ac_status']
            ]);
            echo "Inserted: " . $user['username'] . "<br>";
        } else {
            echo "User already exists: " . $user['username'] . "<br>";
        }
    }

    echo "User data initialization completed successfully.<br>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
    error_log($e->getMessage());
}
?>
