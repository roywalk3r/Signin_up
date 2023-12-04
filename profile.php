<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "User ID not set in the session"]);
  exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, signup_date, profilePic FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

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


      <button type="button" class="login" id="logoutButton">Login</button>

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
        <p>Email: <?php echo $userDetails['username']; ?></p>
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
      <form class="update-form" id="updateForm" method="post" action="update_email.php">
        <div class="log">
          <label for="JoinDate">Join Date: </label>
          <input type="text" name="JoinDate" id="JoinDate" value="<?php echo $userDetails['signup_date']; ?>" readonly>
        </div>
        <div class="log">
          <label for="Email">Email Address: </label>
          <input type="email" name="Email" id="address" value="<?php echo $userDetails['username']; ?>">
        </div>
        <div class="log">
          <label for="Username">Username: </label>
          <input type="text" name="Username" id="username" value="<?php echo $userDetails['username']; ?>">
        </div>
        <div class="log" id="changePasswordOption">
          <a href="#" id="togglePasswordChange">Change Password: </a>
        </div>

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

  <script src="signin_up.js">
  </script>

</body>

</html>