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

    // SQL to create tables and insert initial data
    $table_sql = [
        "CREATE TABLE IF NOT EXISTS admin (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            profile_pic VARCHAR(255) DEFAULT 'images/default-profile-pic.jpg',
            created_at TIMESTAMP NOT NULL DEFAULT current_timestamp()
        )",
        "CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            bio TEXT,
            created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            fullname VARCHAR(255),
            profile_pic VARCHAR(255),
            ac_status TINYINT(1) DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS posts (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            image VARCHAR(255),
            caption TEXT,
            created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id)
        )",
        "CREATE TABLE IF NOT EXISTS followers (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            follower_id INT(11) NOT NULL,
            followed_id INT(11) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            CONSTRAINT fk_followers_follower FOREIGN KEY (follower_id) REFERENCES users(id),
            CONSTRAINT fk_followers_followed FOREIGN KEY (followed_id) REFERENCES users(id)
        )",
        "CREATE TABLE IF NOT EXISTS messages (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            sender_id INT(11) NOT NULL,
            receiver_id INT(11) NOT NULL,
            message TEXT,
            created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id),
            CONSTRAINT fk_messages_receiver FOREIGN KEY (receiver_id) REFERENCES users(id)
        )"
    ];

    // Execute each table creation query
    foreach ($table_sql as $query) {
        $conn->exec($query);
        echo "Executed: " . explode(' ', $query)[0] . " " . explode(' ', $query)[2] . "<br>";
    }

    // Insert initial data into admin table if it's empty
    $stmt = $conn->query("SELECT COUNT(*) FROM admin");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $conn->exec("INSERT INTO admin (id, username, password, email, profile_pic, created_at) VALUES
            (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com', 'images/default-profile-pic.jpg', '2024-07-24 22:54:34')");
        echo "Admin user inserted.<br>";
    }

    echo "Database setup completed successfully.<br>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
    error_log($e->getMessage());
}
?>
