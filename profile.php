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

// Check if the user_id is set in the session
if (!isset($_SESSION['username'])) {
  echo json_encode(["error" => "username not set in the session"]);
  exit;
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT username, signup_date, profilePic, userEmail FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
  $result = $stmt->get_result();
  $userDetails = $result->fetch_assoc();
}

$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="profile.css" />
  <title>Document</title>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>


</head>

<body>
  <header>
    <nav>
      <a href="home.html" class="logo"> Film<span id="flix">Flix</span> </a>
      <?php

      if (isset($userDetails['username'])) {
        // User is logged in, so display the logout button
        echo '<button type="button" class="login" id="logoutButton">Logout</button>';
      } else {
        // User is not logged in, so display the login button
        echo '<button type="button" class="login" id="loginButton">Login</button>';
      }
      ?>
    </nav>
  </header>
  <div class="container">
    <div class="right-sidebar">
      <div class="profile-pic" id="profilePicContainer">

        <img src="<?php echo $userDetails['profilePic']; ?>" alt="Profile Picture" id="profilePic">
        <div class="overlay" id="changePicOverlay">
          <label for="fileInput">Upload Pic</label>
          <input type="file" id="fileInput" accept="image/*" onchange="uploadProfilePic()">
        </div>
      </div>

      <div class="user-detail">
        <p class="welcomeMessage">
          <?php
          if (isset($userDetails['username'])) {
            echo "Welcome, " . $userDetails['username'] . "!";
          } else {
            echo "Welcome!";
          }
          ?>
        </p>
        <p>Email: <?php echo $userDetails['userEmail']; ?> </p>
        <p id="signupDate">Join Date: <?php echo $userDetails['signup_date']; ?></p>
      </div>
      <br>
      <br>
      <div class="logout" id="control">
        <div class="home">
          <a href="home.php" class="home">Go home</a>
        </div>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> &nbsp;Logout</a>
      </div>
    </div>
    <br />

    <div class="main-content">
      <form class="update-form" id="updateForm" method="post" action="#">
        <!-- Existing fields -->
        <div id="messageContainer"></div>

        <div class="log">
          <label for="JoinDate">Join Date: </label>
          <input type="text" name="JoinDate" id="JoinDate" value="<?php echo $userDetails['signup_date']; ?>" readonly>
        </div>
        <div class="log">
          <label for="Email">Email Address: </label>
          <input type="email" name="Email" id="address" placeholder="add email to enable password recovery" value="<?php echo $userDetails['userEmail']; ?>">
        </div>
        <div class="log">
          <label for="Username">Username: </label>
          <input type="text" name="Username" id="username" value="<?php echo $userDetails['username']; ?>">
        </div>

        <!-- Password change option -->
        <div class="log" id="changePasswordOption">
          <label for="changePassword">Change Password: </label>
          <input type="checkbox" id="changePassword" name="changePassword" onchange="togglePasswordFields()">
        </div>

        <!-- Password change fields (initially hidden) -->
        <div id="passwordChangeFields" class="password-fields-hidden">
          <div class="log">
            <label for="currentPassword">Current Password: </label>
            <input type="password" name="currentPassword" id="currentPassword">
          </div>
          <div class="log">
            <label for="newPassword">New Password</label>
            <input type="password" name="newPassword" id="newPassword">
          </div>
        </div>

        <input type="submit" value="Update" id="Submit">
      </form>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
  <script>
    //Upload user Image into db
    function uploadProfilePic() {
      const fileInput = document.getElementById("fileInput");
      const file = fileInput.files[0];

      if (file) {
        const formData = new FormData();
        formData.append("profilePic", file);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "upload_profile_pic.php", true);

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              // Parse the JSON response
              const response = JSON.parse(xhr.responseText);

              if (response.success) {
                // Update the profile picture source after successful upload
                const newProfilePicUrl = response.newPath;
                document.getElementById("profilePic").src = newProfilePicUrl;
              } else {
                // Handle the case when the upload is not successful
                console.error("Upload failed:", response.message);
              }
            } else {
              // Handle non-200 HTTP status
              console.error("HTTP error:", xhr.status);
            }
          }
        };

        xhr.send(formData);
      }
    }
  </script>
  <script src="signin_up.js">
  </script>

</body>

</html>