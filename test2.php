<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: signin_up.html"); // Redirect to your login page
    exit;
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "signin_up";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, signup_date FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
    $stmt->bind_result($username, $signupDate);
    $stmt->fetch();
    $stmt->close();

    // Return data in JSON format
    echo json_encode(["username" => $username, "signupDate" => $signupDate]);
} else {
    echo json_encode(["error" => "Error executing database query"]);
}
