//add hCaptcha manualy
hcaptcha.render("captcha-1", {
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
// Switch Forms

// function switchForm(formIdToShow) {
//   const loginForm = document.getElementById("loginForm");
//   const signupForm = document.getElementById("signupForm");

//   if (formIdToShow === "signupForm") {
//     loginForm.style.opacity = 0;
//     loginForm.style.pointerEvents = "none";

//     signupForm.style.opacity = 1;
//     signupForm.style.pointerEvents = "auto";
//   } else {
//     loginForm.style.opacity = 1;
//     loginForm.style.pointerEvents = "auto";

//     signupForm.style.opacity = 0;
//     signupForm.style.pointerEvents = "none";
//   }
// }

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
