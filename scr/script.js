const passwordInput = document.getElementById('password');
const hideIcon = document.querySelector('.hide-icon');

hideIcon.addEventListener('click', () => {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        hideIcon.src = 'picture/eye-open.png';
    } else {
        passwordInput.type = 'password';
        hideIcon.src = 'picture/eye-close.png';
    }
});

const password = document.getElementById("password");

const lengthRule = document.getElementById("length");
const uppercaseRule = document.getElementById("uppercase");
const lowercaseRule = document.getElementById("lowercase");
const numberRule = document.getElementById("number");

password.addEventListener("keyup", function () {

    const value = password.value;
    if (value.length >= 8 && value.length <= 25) {
        lengthRule.textContent = "✔ 8–25 characters";
        lengthRule.classList.remove("invalid");
        lengthRule.classList.add("valid");
    } else {
        lengthRule.textContent = "✘ 8–25 characters";
        lengthRule.classList.remove("valid");
        lengthRule.classList.add("invalid");
    }
    if (/[A-Z]/.test(value)) {
        uppercaseRule.textContent = "✔ At least 1 uppercase letter";
        uppercaseRule.classList.remove("invalid");
        uppercaseRule.classList.add("valid");
    } else {
        uppercaseRule.textContent = "✘ At least 1 uppercase letter";
        uppercaseRule.classList.remove("valid");
        uppercaseRule.classList.add("invalid");
    }
    if (/[a-z]/.test(value)) {
        lowercaseRule.textContent = "✔ At least 1 lowercase letter";
        lowercaseRule.classList.remove("invalid");
        lowercaseRule.classList.add("valid");
    } else {
        lowercaseRule.textContent = "✘ At least 1 lowercase letter";
        lowercaseRule.classList.remove("valid");
        lowercaseRule.classList.add("invalid");
    }

    if (/[0-9]/.test(value)) {
        numberRule.textContent = "✔ At least 1 number";
        numberRule.classList.remove("invalid");
        numberRule.classList.add("valid");
    } else {
        numberRule.textContent = "✘ At least 1 number";
        numberRule.classList.remove("valid");
        numberRule.classList.add("invalid");
    }

});


