//Change password in profile
$(document).ready(function () {
  // Toggle the visibility of the "Change Password" input field
  $("#changePasswordButton").click(function () {
    $("#passwordField").toggle();
  });
});

//Update Button
$(document).ready(function () {
  // When the "Update" button is clicked
  $("#updateButton").click(function () {
    // Get the new email value from the input field
    var newEmail = $("#email").val();

    // Send an AJAX request to update the email
    $.ajax({
      type: "POST",
      url: "update_email.php", // Replace with the URL of your PHP script
      data: { Email: newEmail },
      success: function (response) {
        // Display the response message
        $("#updateMessage").html(response);
        $("#updateMessage").show();
      },
    });
  });
});

//Logout
$(document).ready(function () {
  $("#logoutButton").click(function () {
    $.ajax({
      type: "POST",
      url: "logout.php",
      success: function (response) {
        // Redirect the user to the login page after successful logout
        window.location.href = "index.php";
      },
    });
  });
});

//ChangePassword Function to call
document.addEventListener("DOMContentLoaded", function () {
  // Get references to the link and password change fields
  var toggleLink = document.getElementById("togglePasswordChange");
  var passwordFields = document.getElementById("passwordChangeFields");

  // Add click event listener to the link
  toggleLink.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the link from navigating

    // Toggle the visibility of password change fields
    if (passwordFields.style.display === "none") {
      passwordFields.style.display = "block";
    } else {
      passwordFields.style.display = "none";
    }
  });
});
//Upload user Image into db
function uploadProfilePic() {
  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];

  if (file) {
    const formData = new FormData();
    formData.append("profilePic", file);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "upload_profile_pic.php", true);

    xhr.onreadystatechange = function () {
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
