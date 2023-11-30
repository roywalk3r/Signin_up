document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("loginform")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      // Verify hCaptcha
      var hcaptchaResponse = grecaptcha.getResponse();

      if (!hcaptchaResponse) {
        // hCaptcha verification failed
        document.getElementById("loginWarnings").innerHTML =
          "hCaptcha verification failed. Please try again.";
        return;
      }

      // Get form data
      var usernameEmail = document.getElementById("usernameEmail").value;
      var loginPassword = document.getElementById("loginPassword").value;

      // Create FormData object
      var formData = new FormData();
      formData.append("usernameEmail", usernameEmail);
      formData.append("loginPassword", loginPassword);
      formData.append("h-captcha-response", hcaptchaResponse); // Include hCaptcha response in the form data

      // Send AJAX request
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "test1.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            // Success response
            document.getElementById("loginWarnings").innerHTML =
              "Success! You are logged in.";
          } else {
            // Error response
            document.getElementById("loginWarnings").innerHTML =
              xhr.responseText;
          }
        }
      };
      xhr.send(formData);
      //   });

      // Pick details from form and send to php file for validation and  return results
      $(document).ready(function () {
        $("#signupform").submit(function (e) {
          e.preventDefault();

          $.ajax({
            type: "POST",
            url: "test.php",
            data: $(this).serialize(),
            dataType: "json", // Expect JSON response
            success: function (response) {
              if (response.status === "success") {
                $("#warnings").html(response.message);
              } else {
                $("#warnings").html("Error: " + response.message);
              }
            },
            error: function () {
              $("#warnings").html("An error occurred. Please try again.");
            },
          });
        });
      });

      // document
      //   .getElementById("loginForm")
      //   .addEventListener("submit", function (event) {
      //     event.preventDefault();

      //     // Verify hCaptcha
      //     var hcaptchaResponse = grecaptcha.getResponse();

      //     if (!hcaptchaResponse) {
      //       // hCaptcha verification failed
      //       document.getElementById("loginWarnings").innerHTML =
      //         "hCaptcha verification failed. Please try again.";
      //       return;
      //     }

      //     // Get form data
      //     var usernameEmail = document.getElementById("usernameEmail").value;
      //     var loginPassword = document.getElementById("loginPassword").value;

      //     // Create FormData object
      //     var formData = new FormData();
      //     formData.append("usernameEmail", usernameEmail);
      //     formData.append("loginPassword", loginPassword);
      //     formData.append("h-captcha-response", hcaptchaResponse); // Include hCaptcha response in the form data

      //     // Send AJAX request
      //     var xhr = new XMLHttpRequest();
      //     xhr.open("POST", "test1.php", true);
      //     xhr.onreadystatechange = function () {
      //       if (xhr.readyState == 4) {
      //         if (xhr.status == 200) {
      //           // Success response
      //           document.getElementById("loginWarnings").innerHTML =
      //             "Success! You are logged in.";
      //         } else {
      //           // Error response
      //           document.getElementById("loginWarnings").innerHTML =
      //             xhr.responseText;
      //         }
      //       }
      //     };
      //     xhr.send(formData);
      //   });
    });
});
