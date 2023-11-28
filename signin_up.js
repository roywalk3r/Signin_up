document.addEventListener("DOMContentLoaded", function () {
  // recaptcha

  // Your server-side verification endpoint
  const verifyCaptcha = async (token) => {
    const secretKey = "6LePOO8nAAAAAFzgyeRE1MVa4er7jDNRXDPgJpVu";
    const verificationURL = `https://www.google.com/recaptcha/api/siteverify?secret=${secretKey}&response=${captchaResponse}`;
    try {
      const response = await fetch(verificationURL, {
        method: "POST",
      });

      const data = await response.json();
      if (data.success) {
        // CAPTCHA verification successful, proceed with form submission
        console.log("CAPTCHA verification successful");
      } else {
        // CAPTCHA verification failed
        console.error("CAPTCHA verification failed");
      }
    } catch (error) {
      console.error("Error during CAPTCHA verification:", error);
    }
  };
});
