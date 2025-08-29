<?php
// DB connection settings
$host = "localhost";
$dbname = "temple_darshan";
$username = "root"; // change if needed
$password = "";     // change if needed

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'];
$email = $_POST['email'];
$temple_id = $_POST['temple_id'];
$booking_date = $_POST['booking_date'];

// Validate input
if (!$name || !$email || !$temple_id || !$booking_date) {
    die("All fields are required.");
}

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $user_id = $user['id'];
} else {
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $user_id = $stmt->insert_id;
}

// Insert booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, temple_id, booking_date) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $temple_id, $booking_date);
$success = $stmt->execute();

if ($success) {
    echo "<script>alert('Darshan booking successful!'); window.location.href='index.html';</script>";
} else {
    echo "Booking failed. Please try again.";
}

$conn->close();
?>