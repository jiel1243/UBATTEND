// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});

// QR Code Generation
function generateQRCode() {
    const student_id = document.getElementById("student_id").value;
    const student_name = document.getElementById("student_name").value;
    const subject = document.getElementById("subject");
    const qrcodeDiv = document.getElementById("qrcode");

    // Validate subject selection
    if (!subject.value) {
        subject.classList.add('is-invalid');
        return;
    }

    subject.classList.remove('is-invalid');

    // Create QR data object
    const qr_data = {
        student_id: student_id,
        student_name: student_name,
        subject: subject.value,
        entry_time: new Date().toISOString().slice(0, 19).replace("T", " ")
    };

    // Store data in sessionStorage
    sessionStorage.setItem('qrData', JSON.stringify(qr_data));

    // Show loading state
    qrcodeDiv.style.display = 'block';
    qrcodeDiv.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

    // Redirect to QR display page with small delay for better UX
    setTimeout(() => {
        window.location.href = "qr_display.php";
    }, 500);
}

// Handle subject change
document.getElementById('subject').addEventListener('change', function() {
    this.classList.remove('is-invalid');
});