<?php
session_start();

// Function to safely sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(stripslashes(trim($input)));
}

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to your login page
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Add your database password here
$dbname = "signin_up";
// $2y$12$GIr5tSo35yno56WDhNV.7uHNV98WNhiuv6GXyYP/d/i3sFSTgtlR.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['userEmail'];
    $signupDate = $row['signup_date'];
    // Add more user data as needed
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = sanitizeInput($_POST['Email']);
    $newUsername = sanitizeInput($_POST['Username']);

    // Check if the "Change Password" checkbox is checked
    if (isset($_POST['changePassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        // Validate the current password
        if (password_verify($currentPassword, $row['password'])) {
            // Current password is correct, update the email, username, and password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET userEmail = '$newEmail', username = '$newUsername', password = '$hashedNewPassword' WHERE username = '$username'";

            if ($conn->query($updateSql) === TRUE) {
                echo '<p id="successText" style="color: green">Profile updated successfully.</p>';
            } else {
                echo '<p id="errorText" style="color: red">Error updating profile.</p>';
            }
        } else {
            echo '<p id="errorText" style="color: red">Incorrect current password.</p>';
        }
    } else {
        // Checkbox not checked, update only email and username
        $updateSql = "UPDATE users SET userEmail = '$newEmail', username = '$newUsername' WHERE username = '$username'";

        if ($conn->query($updateSql) === TRUE) {
            $_SESSION['username'] = $newUsername;

            echo '<p id="successText" style="color: green">Profile updated successfully. Refresh Page</p>';
        } else {
            echo '<p id="errorText" style="color: red">Error updating profile.</p>';
        }
    }
}

// Close the database connection
$conn->close();
