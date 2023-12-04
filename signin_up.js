//Change password in profile
// $(document).ready(function () {
//   // Toggle the visibility of the "Change Password" input field
//   $("#changePasswordButton").click(function () {
//     $("#passwordField").toggle();
//   });
// });

function togglePasswordFields() {
  var passwordFields = document.getElementById("passwordChangeFields");
  var checkbox = document.getElementById("changePassword");

  if (checkbox.checked) {
    passwordFields.style.display = "block";
  } else {
    passwordFields.style.display = "none";
  }
}
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

//Logout
$(document).ready(function () {
  $("#logoutButton").click(function () {
    $.ajax({
      type: "POST",
      url: "logout.php", // Replace with the URL of your logout script
      success: function (response) {
        // Redirect the user to the login page after successful logout
        window.location.href = "logout.php"; // Replace with your login page URL
      },
    });
  });
});

//Ajax For the update details
// jQuery function to handle form submission
$(document).ready(function () {
  $("#updateForm").submit(function (event) {
    // Prevent the default form submission
    event.preventDefault();

    // Perform AJAX request
    $.ajax({
      type: "POST",
      url: "update_profile.php", // Specify your PHP file
      data: $("#updateForm").serialize(), // Serialize form data
      success: function (response) {
        $("#messageContainer").html(response); // Update the message container
        if (response.status === "success") {
          $("#JoinDate").val(response.userDetails.signup_date);
          $("#address").val(response.userDetails.userEmail);
          $("#username").val(response.userDetails.username);
        }
      },

      error: function (error) {
        // console.log(error); // Log any errors to the console
      },
    });
  });
});
