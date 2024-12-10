<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB ATTEND - QR Code Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="qr.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <div class="logo d-flex align-items-center">
                <img src="img/bglogo.png" alt="Logo" class="logo-img">
                <h1 class="mb-0 ms-2">UB ATTEND</h1>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html"><i class="fas fa-home me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user me-1"></i>Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="back-button mb-4">
                    <a href="index.html" class="btn btn-link">
                        <i class="fas fa-chevron-left me-2"></i>Back to Home
                    </a>
                </div>
                
                <!-- QR Card -->
                <div class="qr-card">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-qrcode me-2"></i>Attendance QR Code
                    </h2>
                    <div class="qr-container">
                        <div id="qrcode"></div>
                    </div>
                    <p class="text-center mt-4">
                        Please scan this QR code using the designated scanner
                    </p>
                </div>

                <!-- System Features -->
                <div class="row mt-5">
                    <div class="col-md-6 mb-4">
                        <div class="feature-card">
                            <i class="fas fa-clock feature-icon"></i>
                            <h3>Real-Time Tracking</h3>
                            <p>Instant attendance monitoring for students and faculty</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="feature-card">
                            <i class="fas fa-shield-alt feature-icon"></i>
                            <h3>Secure & Reliable</h3>
                            <p>Advanced security measures to prevent proxy attendance</p>
                        </div>
                    </div>
                </div>

                <!-- About System -->
                <div class="about-section">
                    <h2 class="mb-4">
                        <i class="fas fa-info-circle me-2"></i>About UB ATTEND
                    </h2>
                    <p>
                        UB ATTEND is the University of Batangas' modern QR-based attendance
                        monitoring system. It streamlines the attendance tracking process while
                        ensuring accuracy and reliability in recording student presence in
                        classes and events.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3>UB ATTEND</h3>
                    <p>A modern attendance monitoring solution by the University of Batangas</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Quick Links</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Contact</h3>
                    <p>
                        University of Batangas<br>
                        Batangas City, Philippines<br>
                        Email: support@ub.edu.ph
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 University of Batangas. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        // Get QR data from sessionStorage
        var qr_data = JSON.parse(sessionStorage.getItem('qrData'));

        // Generate the QR code
        new QRCode(document.getElementById("qrcode"), {
            text: JSON.stringify(qr_data || 'default'),
            width: 200,
            height: 200
        });
    </script>
</body>
</html>