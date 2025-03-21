<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakery_db";

// Start session
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $entered_password = $_POST["password"];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $stored_hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($entered_password, $stored_hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["full_name"] = $full_name;
            echo "Login successful! Welcome, " . $full_name;
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "No account found with this email.";
    }

    // Close statement & connection
    $stmt->close();
    $conn->close();
}
?>
