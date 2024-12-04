function sendOTP() {
    const email = document.getElementById('email').value;
    const otpVerifySection = document.querySelector('.email-verify');
    const otpInput = document.getElementById('otp-input');
    const verifyButton = document.getElementById('otp_btn');

    // Generate a 4-digit OTP
    const otpCode = Math.floor(1000 + Math.random() * 9000);
    const emailBody = `<h2>Your OTP is:</h2><h3>${otpCode}</h3>`;

    // Use SMTP.js to send the email
    Email.send({
        SecureToken: "ac71f941-30bc-4367-9457-154377e88cfa",
        To: email,
        From: "linrebriley@gmail.com",
        Subject: "Your OTP Code",
        Body: emailBody,
    }).then((message) => {
        if (message === "OK") {
            alert("OTP sent to your email: " + email);

            otpVerifySection.style.display = "flex";
            verifyButton.style.display = "block";

            verifyButton.addEventListener("click", () => {
                if (otpInput.value == otpCode) {
                    alert("OTP Verified Successfully!");
                    otpVerifySection.style.display = "none";
                    document.getElementById('email').value = "";
                    otpInput.value = "";
                } else {
                    alert("Invalid OTP. Please try again.");
                }
            });
        } else {
            alert("Failed to send OTP. Please try again.");
        }
    }).catch((error) => {
        console.error("Error sending OTP:", error);
        alert("An error occurred while sending OTP. Please try again.");
    });
}

document.getElementById("forgotPasswordForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const form = new FormData(this);

    fetch("forgot password.php", {
        method: "POST",
        body: form,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                sendOTP();
            } else {
                alert(data.error);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
});
