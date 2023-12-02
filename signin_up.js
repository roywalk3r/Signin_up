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
