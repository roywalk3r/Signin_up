//add hCaptcha manualy
hcaptcha.render("captcha-1", {
  sitekey: "13c39413-cf3c-4bc7-aacd-da5274d158d9",
  theme: "dark",
  "error-callback": "onError",
});

hcaptcha.render("h-captcha", {
  sitekey: "13c39413-cf3c-4bc7-aacd-da5274d158d9",
  theme: "dark",
  "error-callback": "onError",
});
// Toggle Password Visible And Hide

function togglePassword(inputId) {
  const passwordInput = document.getElementById(inputId);
  const eyeIcon = passwordInput.nextElementSibling;

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    eyeIcon.classList.remove("fa-eye-slash");
    eyeIcon.classList.add("fa-eye");
  } else {
    passwordInput.type = "password";
    eyeIcon.classList.remove("fa-eye");
    eyeIcon.classList.add("fa-eye-slash");
  }
}

// Switch form between login and signup
function switchForm(formIdToShow) {
  const loginForm = document.getElementById("loginForm");
  const signupForm = document.getElementById("signupForm");

  if (formIdToShow === "signupForm") {
    // Remove active class from loginForm
    loginForm.classList.remove("active");
    // Add active class to signupForm
    signupForm.classList.add("active");
  } else {
    // Add active class to loginForm
    loginForm.classList.add("active");
    // Remove active class from signupForm
    signupForm.classList.remove("active");
  }
}

//Show the form when button is clicked
function toggleSignInUpForm() {
  var formContainer = document.getElementById("formContainer");
  formContainer.style.display = "flex";
  formContainer.classList.remove("hidden");
}

function toggleHideForm() {
  var hideForm = document.getElementById("formContainer");
  hideForm.classList.toggle("hidden"); // Toggle the 'hidden' class
  hideForm.classList.add("hidden");
}
