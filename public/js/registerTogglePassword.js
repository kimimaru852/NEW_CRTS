function togglePassword() {
    let Password = document.getElementById('password');
    let ConfirmPasswordInput = document.getElementById('password_confirmation');
    let checkbox = document.getElementById('show-password');

    Password.type = checkbox.checked ? "text" : "password";
    ConfirmPasswordInput.type = checkbox.checked ? "text" : "password";
}