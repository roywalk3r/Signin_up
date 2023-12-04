document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("loginform")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      // Function to clear all warnings
      function clearAllWarnings() {
        document.getElementById("loginWarnings").innerHTML = "";
      }
      // Get form data
      var usernameEmail = document.getElementById("usernameEmail").value;
      var loginPassword = document.getElementById("loginPassword").value;
      document.getElementById("loginLoading").style.display = "none";

      // Get hCaptcha response
      var hcaptchaResponse = grecaptcha.getResponse();

      // Create FormData object
      var formData = new FormData(this); // Use 'this' to refer to the form

      formData.append("usernameEmail", usernameEmail);
      formData.append("loginPassword", loginPassword);
      formData.append("h-captcha-response", hcaptchaResponse);

      // Show loading spinner
      document.getElementById("loginLoading").style.display = "flex";

      // Send AJAX request
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "test1.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          // Hide loading spinner
          document.getElementById("loginLoading").style.display = "none";

          if (xhr.status == 200) {
            if (xhr.responseText.trim() === "Login Successful") {
              // Clear input fields after successful submission
              document.getElementById("usernameEmail").value = "";
              document.getElementById("loginPassword").value = "";
              grecaptcha.reset(); // Reset reCAPTCHA
              document.getElementById("loginWarnings").innerHTML =
                "Login successful";
              window.location.href = "profile.php"; // Redirect only on successful login
            } else {
              document.getElementById("loginWarnings").innerHTML =
                xhr.responseText;
            }
          }
        }
      };
      xhr.send(formData);
      // Event listener for typing (keyup event)
      document
        .getElementById("usernameEmail")
        .addEventListener("keyup", clearAllWarnings);
      document
        .getElementById("loginPassword")
        .addEventListener("keyup", clearAllWarnings);
    });

  //SignuP Form
  // Pick details from form and send to php file for validation and  return results
  $(document).ready(function () {
    // Function to clear all signup warnings
    function clearAllSignUpWarnings() {
      $("#loginWarnings").html(""); // Use jQuery to clear warnings
    }

    // Event listener for typing (input event)
    $(
      "#usernameEmail, #signupPassword, #confirmPassword, #h-captcha-response"
    ).on("input", clearAllSignUpWarnings);

    $("#signupform").submit(function (e) {
      e.preventDefault();

      // Show loading spinner
      $("#signupLoading").css("display", "flex");

      $.ajax({
        type: "POST",
        url: "test.php",
        data: $(this).serialize(),
        dataType: "json", // Expect JSON response
        success: function (response) {
          $("#signupLoading").css("display", "none");

          if (response.status === "success") {
            // Clear input fields after successful submission
            $("#usernameEmail, #signupPassword, #confirmPassword").val("");

            // Reset hCaptcha
            grecaptcha.reset();
            $("#warnings").html(response.message);
          } else {
            $("#warnings").html("Error: " + response.message);
          }
        },
        error: function () {
          $("#signupLoading").css("display", "none");
          $("#warnings").html(
            "An error occurred." + response.message,
            "Please try again."
          );
        },
      });
    });
  });
});
