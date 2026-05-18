function togglePassword() {
    let passwordInput = document.getElementById('password');
    let checkbox = document.getElementById('show-password');

    passwordInput.type = checkbox.checked ? "text" : "password";
}
