document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("loginform")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Get form data
      var usernameEmail = document.getElementById("usernameEmail").value;
      var loginPassword = document.getElementById("loginPassword").value;

      // Create FormData object
      var formData = new FormData();
      formData.append("usernameEmail", usernameEmail);
      formData.append("loginPassword", loginPassword);

      // Send AJAX request
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "test1.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          document.getElementById("loginWarnings").innerHTML = xhr.responseText;
        }
      };
      xhr.send(formData);
    });

  // Pick details from form and send to php file for validation and  return results
  $(document).ready(function () {
    $("#signupform").submit(function (e) {
      e.preventDefault();

      $.ajax({
        type: "POST",
        url: "test.php",
        data: $(this).serialize(),
        success: function (response) {
          $("#warnings").html(response);
        },
        error: function () {
          $("#warnings").html("An error occurred. Please try again.");
        },
      });
    });
  });
});
