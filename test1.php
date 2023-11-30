<?php
session_start(); // Start the session for managing user authentication

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate hCaptcha
    $hcaptcha_secret_key = "6LcG8SEpAAAAAH6qnkMqbqNJOLebmTMmS4Z8H38z"; // Replace with your actual hCaptcha secret key
    $hcaptcha_response = $_POST["g-recaptcha-response"];
    $hcaptcha_url = "https://www.google.com/recaptcha/api/siteverify";

    $hcaptcha_data = [
        'secret' => $hcaptcha_secret_key,
        'response' => $hcaptcha_response
    ];

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, $hcaptcha_url);
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($hcaptcha_data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($verify);
    curl_close($verify);

    $responseData = json_decode($response);

    if (!$responseData->success) {
        echo "hCaptcha verification failed. Please try again.";
        exit;
    }

    // Check if the required keys are set in the $_POST array
    if (isset($_POST["usernameEmail"], $_POST["loginPassword"])) {
        // Sanitize and validate username/email and password
        $usernameEmail = sanitizeInput($_POST["usernameEmail"]);
        $password = sanitizeInput($_POST["loginPassword"]);

        // Database connection
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "signin_up";

        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user data from the database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $usernameEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();

        if ($userData && password_verify($password, $userData['password'])) {
            // Authentication successful
            $_SESSION['user_id'] = $userData['id']; // Store user ID in session for future use
            echo "success";
        } else {
            // Authentication failed
            echo "error|Invalid username/email or password. Please try again.";
        }

        $conn->close();
    } else {
        echo "error|Incomplete form submission. Please fill in all required fields.";
    }
} else {
    echo "error|Invalid request method.";
}
