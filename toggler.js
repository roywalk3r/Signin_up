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

function switchForm(formIdToShow) {
  const loginForm = document.getElementById("loginForm");
  const signupForm = document.getElementById("signupForm");

  if (formIdToShow === "signupForm") {
    loginForm.classList.remove("active");
    signupForm.classList.add("active");
  } else {
    loginForm.classList.add("active");
    signupForm.classList.remove("active");
  }
}
