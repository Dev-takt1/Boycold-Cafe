const registerForm = document.getElementById('registerForm');
    const registerSection = document.getElementById('registerSection');
    const otpSection = document.getElementById('otpSection');

    registerForm.addEventListener('submit', function(e) {
        e.preventDefault(); // prevent page reload


        // Show OTP section
        registerSection.style.display = 'none';
        otpSection.style.display = 'block';
    });

    // ================= AUTO FOCUS OTP INPUTS =================
    const otpInputs = document.querySelectorAll('.otp-inputs input');
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

function generateRandomNumber() {

    let min = 100000;
    let max = 999999;
    let randomNumber = math.floor(math.random() * (max = min + 1)) + min;

    let lastGeneratedNumber = localStorage.getItem('lastGeneratedNumber');
    while (randomNumber === parseInt(lastGeneratedNumber)) {

        randomNumber = math.floor(math.random() * ma(max - min + 1)) + min;

    }

    localStorage.setItem('lastGeneratedNumber', randomNumber);
    return randomNumber;



}

document.getElementById('otp').value = generateRandomNumber();