document.addEventListener("DOMContentLoaded", function() {
    // Get all input fields and the submit button
    const inputs = document.querySelectorAll('input');
    const submitButton = document.getElementById('submitButton');

    // Function to check if any input is empty
    function checkForm() {
        let formValid = true;
        inputs.forEach(input => {
            if (input.value === "") {
                formValid = false;
            }
        });

        // Enable/Disable the submit button based on form validity
        if (formValid) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Event listeners for inputs to check the form whenever input is changed
    inputs.forEach(input => {
        input.addEventListener('input', checkForm);
    });

    // Run the checkForm function when the page loads to set the initial state
    checkForm();
});