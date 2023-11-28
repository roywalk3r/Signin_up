<?php
session_start(); // Start the session for managing user authentication

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the hCaptcha
    $hcaptcha_secret_key = "ES_5df64bdda3b6418d8a6fc4336c14b22d";
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
