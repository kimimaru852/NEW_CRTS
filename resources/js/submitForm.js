// Function to handle AJAX form submission
function handleFormSubmission(formId, submitUrl, successRedirectUrl = null) {
    // Get the form element by id
    const form = document.getElementById(formId);

    if (!form) {
        console.error(`Form with ID "${formId}" not found.`);
        return;
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        const formData = new FormData(form);
        const errorFields = Array.from(form.querySelectorAll('.error'));

        // Clear previous error messages
        errorFields.forEach(error => error.textContent = '');

        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                // Display validation errors if any
                for (let field in data.errors) {
                    const errorMessage = data.errors[field].join('<br>');
                    const errorElement = document.getElementById(`${field}-error`);
                    if (errorElement) {
                        errorElement.innerHTML = errorMessage;
                    }
                }
            } else if (data.success) {
                // Redirect if no validation errors and success response
                if (successRedirectUrl) {
                    window.location.href = successRedirectUrl;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
}
