<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile from the database
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT student_id, student_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
    $student_id = $user["student_id"];
    $student_name = $user["student_name"];
} else {
    echo "Error: User not found.";
    exit;
}

// Fetch subjects from the database for the dropdown menu
$subjects_query = "SELECT subject_code, subject_name FROM subjects";
$subjects_result = $conn->query($subjects_query);
$subjects = [];

if ($subjects_result->num_rows > 0) {
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    echo "No subjects found.";
    exit;
}

// Close the connection after all queries are done
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB ATTEND - QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <div class="navbar-brand d-flex align-items-center">
                <img src="img/bglogo.png" alt="UB Logo" class="logo-img me-2">
                <span>UB ATTEND</span>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card main-card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-qrcode me-2"></i>Generate QR Code</h4>
                    </div>
                    <div class="card-   body">
                        <form id="qr-form" class="needs-validation" novalidate>
                            <!-- Student Information Section -->
                            <div class="form-section mb-4">
                                <h5 class="section-title">Student Information</h5>
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" id="student_id" class="form-control" value="<?php echo htmlspecialchars($student_id); ?>" disabled>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="student_name" class="form-label">Student Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" id="student_name" class="form-control" value="<?php echo htmlspecialchars($student_name); ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Selection Section -->
                            <div class="form-section mb-4">
                                <h5 class="section-title">Course Details</h5>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Select Subject</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select id="subject" class="form-select" required>
                                        <option value="">--Choose a Subject--</option>
                                        <?php if (isset($subjects) && !empty($subjects)): ?>
                                        <?php foreach ($subjects as $subject): ?>
                                       <option value="<?php echo htmlspecialchars($subject['subject_code']); ?>">
                                         <?php echo htmlspecialchars($subject['subject_code']) . " - " . htmlspecialchars($subject['subject_name']); ?>
                                        </option>
                                         <?php endforeach; ?>
                                      <?php else: ?>
                                     <option value="">No subjects available</option>
                                         <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a subject</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Generate Button -->
                            <button type="button" class="btn btn-generate w-100" onclick="generateQRCode()">
                                <i class="fas fa-qrcode me-2"></i>Generate QR Code
                            </button>
                        </form>

                        <!-- QR Code Display -->
                        <div id="qrcode" class="text-center mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script2.js"></script>
</body>
</html>