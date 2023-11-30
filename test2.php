<?php
session_start(); // Start the session for managing user authentication

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the hCaptcha
    $hcaptcha_secret_key = "ES_5df64bdda3b6418d8a6fc4336c14b22d"; // Replace with your actual hCaptcha secret key
    $hcaptcha_response = $_POST["h-captcha-response"];
    $hcaptcha_url = "https://hcaptcha.com/siteverify";

    $hcaptcha_data = array(
        'secret' => $hcaptcha_secret_key,
        'response' => $hcaptcha_response
    );

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
    if (isset($_POST["usernameEmail"], $_POST["signupPassword"], $_POST["confirmPassword"])) {
        // Sanitize and validate username/email and password
        $usernameEmail = sanitizeInput($_POST["usernameEmail"]);
        $password = sanitizeInput($_POST["signupPassword"]);
        $confirmPassword = sanitizeInput($_POST["confirmPassword"]);

        // Determine if the input is an email or a username
        if (filter_var($usernameEmail, FILTER_VALIDATE_EMAIL)) {
            $email = $usernameEmail;
            $username = null; // You can set a default or leave it null if not applicable
        } else {
            $username = $usernameEmail;
            $email = null; // You can set a default or leave it null if not applicable
        }

        // Perform additional validations if needed
        // For example, check password strength, validate email format, etc.

        if ($password != $confirmPassword) {
            echo "Passwords do not match. Please try again.";
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

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

        // Check if username or email already exists in the database
        $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkQuery->bind_param("s", $username,);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            echo "Username or email already exists.";
            exit;
        }

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } elseif (isset($_POST["usernameEmail"], $_POST["loginPassword"])) {
        // Login form

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
            echo "Login successful!";
        } else {
            // Authentication failed
            echo "Invalid username/email or password. Please try again.";
        }

        $conn->close();
    } else {
        echo "Incomplete form submission. Please fill in all required fields.";
    }
} else {
    echo "Invalid request method.";
}

error_log(print_r($_POST, true));
