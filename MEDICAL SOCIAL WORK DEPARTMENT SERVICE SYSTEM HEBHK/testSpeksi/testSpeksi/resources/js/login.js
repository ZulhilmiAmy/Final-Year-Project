let captchaCode = "";

function generateCaptcha() {
    const canvas = document.getElementById("captchaCanvas");
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    captchaCode = "";
    for (let i = 0; i < 5; i++) {
        captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    ctx.font = "bold 20px Poppins";
    ctx.fillStyle = "#2c3e50";
    ctx.textBaseline = "middle";
    ctx.fillText(captchaCode, 15, 20);

    document.getElementById("captcha-session").value = captchaCode;
}

function resetForm() {
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
    document.getElementById("captcha-input").value = "";
    generateCaptcha();
}

document.addEventListener("DOMContentLoaded", function () {
    generateCaptcha();
    const showPasswordCheckbox = document.getElementById("show-password");
    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener("change", function () {
            const passwordInput = document.getElementById("password");
            passwordInput.type = this.checked ? "text" : "password";
        });
    }
});
