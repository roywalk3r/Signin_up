document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("loginform")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Show loading spinner
      document.getElementById("loginLoading").style.display = "inline-block";

      // Get form data
      var usernameEmail = document.getElementById("usernameEmail").value;
      var loginPassword = document.getElementById("loginPassword").value;

      // Get hCaptcha response
      var hcaptchaResponse = grecaptcha.getResponse();

      // Create FormData object
      var formData = new FormData(this); // Use 'this' to refer to the form

      formData.append("usernameEmail", usernameEmail);
      formData.append("loginPassword", loginPassword);
      formData.append("h-captcha-response", hcaptchaResponse);

      // Send AJAX request
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "test1.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          // Hide loading spinner
          document.getElementById("loginLoading").style.display = "none";

          if (xhr.status == 200) {
            // Clear input fields after successful submission
            document.getElementById("usernameEmail").value = "";
            document.getElementById("loginPassword").value = "";
            grecaptcha.reset(); // Reset reCAPTCHA
            document.getElementById("loginWarnings").innerHTML =
              xhr.responseText;
          }
        }
      };
      xhr.send(formData);
    });

  // Pick details from form and send to php file for validation and  return results
  $(document).ready(function () {
    $("#signupform").submit(function (e) {
      e.preventDefault();
      // Show loading spinner
      $("#signupLoading").addClass("active");

      $.ajax({
        type: "POST",
        url: "test.php",
        data: $(this).serialize(),
        dataType: "json", // Expect JSON response
        success: function (response) {
          if (response.status === "success") {
            // Clear input fields after successful submission
            document.getElementById("usernameEmail").value = "";
            document.getElementById("signupPassword").value = "";
            document.getElementById("confirmPassword").value = "";
            grecaptcha.reset(); // Reset reCAPTCHA
            $("#warnings").html(response.message);
          } else {
            $("#warnings").html("Error: " + response.message);
          }
        },
        error: function () {
          $("#signupLoading").removeClass("active");

          $("#warnings").html("An error occurred. Please try again.");
        },
      });
    });
  });
});
