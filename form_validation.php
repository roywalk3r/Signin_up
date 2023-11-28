<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input data
    function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo "Connection failed: " . $conn->connect_error;
        exit();
    }
    // Function to generate a strong hash for passwords
    function generateHash($password)
    {
        // Use a strong hashing algorithm like bcrypt
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    // Check if passwords match
    $password = sanitizeInput($_POST["password"]);
    $confirmPassword = sanitizeInput($_POST["confirmPassword"]);
    // if ($password != $confirmPassword) {
    //     echo json_encode(["error" => "Passwords do not match"]);
    //     exit();
    // }

    // Check reCAPTCHA response
    $recaptchaSecretKey = "6LePOO8nAAAAAFzgyeRE1MVa4er7jDNRXDPgJpVu"; // Replace with your actual secret key
    $recaptchaResponse = sanitizeInput($_POST["g-recaptcha-response"]);

    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    ];

    $recaptchaOptions = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($recaptchaData),
        ],
    ];

    $recaptchaContext = stream_context_create($recaptchaOptions);
    $recaptchaResult = file_get_contents($recaptchaVerifyUrl, false, $recaptchaContext);
    $recaptchaResultData = json_decode($recaptchaResult, true);

    if (!$recaptchaResultData['success']) {
        echo json_encode(["error" => "reCAPTCHA verification failed. Please try again."]);
        exit();
    }

    // Check if the username or email already exists in the database (replace with your database connection logic)
    $usernameEmail = sanitizeInput($_POST["usernameEmail"]);
    $hashedPassword = generateHash($password); // Hash the password before checking in the database

    // Replace the following with your database connection logic
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "signin_up";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username or email already exists
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkQuery->bind_param("ss", $usernameEmail, $usernameEmail);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo json_encode(["error" => "Username or email already exists"]);
        exit();
    }

    $checkQuery->close();

    // If all checks pass, you can proceed with user registration or other actions

    // For testing purposes, you can echo a success message
    echo json_encode(["success" => "Validation successful. You can proceed with registration."]);

    // Close the database connection
    $conn->close();
} else {
    http_response_code(400); // Bad Request
    echo "Invalid request method";
}
