document.getElementById("openScannerBtn").addEventListener("click", function () {
    document.getElementById("qrScanner").classList.remove("hidden");
    startQRScanner();
});

document.getElementById("closeScannerBtn").addEventListener("click", function () {
    document.getElementById("qrScanner").classList.add("hidden");
    stopQRScanner();
});

function startQRScanner() {
    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        qrCodeMessage => {
            const qrData = JSON.parse(qrCodeMessage);
            const subject = "WS101"; // Replace with the actual subject code based on your context

            fetch("process_qr.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ qr_data: qrData, subject: subject })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateRecentActivity(data.student_name, data.subject, data.time);
                } else {
                    alert("Failed to record attendance: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        },
        errorMessage => {
            console.error("QR Code no longer in front of camera.", errorMessage);
        }
    )
}

function stopQRScanner() {
    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.stop().catch(error => console.error("Failed to stop QR code scanner.", error));
}

function updateRecentActivity(student_name, subject, time) {
    const activityList = document.querySelector(".activity-list");
    const activityItem = document.createElement("div");
    activityItem.classList.add("activity-item");
    activityItem.innerHTML = `
        <div class="activity-details">
            <p><strong>${student_name}</strong> attended ${subject}</p>
            <small>${time}</small>
        </div>
    `;
    activityList.prepend(activityItem);
}
