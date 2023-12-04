<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once 'signin_up_conn.php';

function updateProfilePicture($conn, $username, $file)
{
    $uploadDirectory = 'uploads/';

    // Generate a unique filename
    $fileName = uniqid() . '_' . basename($file['name']);
    $fileTempName = $file['tmp_name'];
    $filePath = $uploadDirectory . $fileName;

    // Check if the file is an image
    if (exif_imagetype($fileTempName)) {
        // Get the current profile picture path
        $selectQuery = "SELECT profilePic FROM users WHERE username = ?";
        $stmtSelect = mysqli_prepare($conn, $selectQuery);
        mysqli_stmt_bind_param($stmtSelect, "s", $username);
        mysqli_stmt_execute($stmtSelect);
        mysqli_stmt_bind_result($stmtSelect, $oldFilePath);
        mysqli_stmt_fetch($stmtSelect);
        mysqli_stmt_close($stmtSelect);

        // Move the file to the upload directory
        move_uploaded_file($fileTempName, $filePath);

        // Update the database with the new file path
        $updateQuery = "UPDATE users SET profilePic = ? WHERE username = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, "ss", $filePath, $username);

        if (mysqli_stmt_execute($stmtUpdate)) {
            // Return a success response with the new path
            echo json_encode(['success' => true, 'newPath' => $filePath]);

            // Delete the old profile picture
            if (!empty($oldFilePath) && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        } else {
            // Return an error response
            echo json_encode(['success' => false, 'message' => 'Error updating database']);
        }

        mysqli_stmt_close($stmtUpdate);
    } else {
        // Return an error response
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    }
}

// Assuming you have a session started
session_start();

// Get the username from the session or wherever it's stored
$username = $_SESSION['username'];  // Adjust this based on your authentication mechanism

// Check if a file was uploaded
if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    updateProfilePicture($conn, $username, $_FILES['profilePic']);
} else {
    // Return an error response
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
}

// Set the content type and exit
exit();
