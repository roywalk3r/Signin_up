// ajax.js

// $(document).ready(function () {
//   $("#signupform").submit(function (event) {
//     event.preventDefault();

//     // Clear previous warnings
//     $("#warnings").empty();

//     // Perform AJAX request
//     $.ajax({
//       type: "POST",
//       url: "form_validation.php", // Replace with the actual file name
//       data: {
//         usernameEmail: $("#signupform input[name='usernameEmail']").val(),
//         password: $("#signupform input[name='password']").val(),
//         confirmPassword: $("#signupform input[name='confirmPassword']").val(),
//         "g-recaptcha-response": grecaptcha.getResponse(), // Fetch reCAPTCHA response
//       },
//       dataType: "json", // Expect JSON response
//       success: function (response, textStatus, jqXHR) {
//         if (jqXHR.status === 200) {
//           // Successful response
//           if (response.success) {
//             // Display success message on the form
//             alert(response.success);
//             // You can update the UI or show a success message here
//           } else {
//             console.log("Unexpected response format:", response);
//           }
//         } else {
//           // Error response
//           $("#warnings").html(
//             "<p class='warning'>" + jqXHR.responseText + "</p>"
//           );
//         }
//       },
//       error: function (jqXHR, textStatus, errorThrown) {
//         console.log("AJAX Error:", textStatus, errorThrown);
//       },
//     });
//   });
// });


