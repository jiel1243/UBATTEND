<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for creating a subject and table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $subject_code = $conn->real_escape_string($_POST["subject_code"]);
    $subject_name = $conn->real_escape_string($_POST["subject_name"]);

    // Create the attendance table for the subject
    $table_name = "attendance_" . strtolower($subject_code);
    $create_table_sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        entry_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) NOT NULL
    )";

    if ($conn->query($create_table_sql) === TRUE) {
        echo "Table for $subject_name created successfully.";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    // Insert the subject into the subjects table (id will auto-increment)
    // No need to specify the id in the INSERT query
    $stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $subject_code, $subject_name);
    
    if ($stmt->execute()) {
        echo "Subject added successfully!";
    } else {
        echo "Error inserting subject: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB Teacher Dashboard - Manage Classes</title>
    <link rel="stylesheet" href="dash.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="img/bglogo.png" alt="UB Logo" class="logo">
            <span class="logo-text">University of Batangas</span>
        </div>
        <div class="nav-links">
            <a href="#" class="active">Dashboard</a>
            <a href="#">Profile</a>
            <a href="index.html" class="logout">Logout</a>
        </div>
    </nav>

    <div class="hero-section">
        <div class="hero-content">
            <h1>Welcome Teacher</h1>
            <p>Manage your class attendance and records</p>
        </div>
    </div>

    <div class="container">
        <div class="main-actions">
            <div class="action-card">
                <img src="https://img.freepik.com/free-vector/data-extraction-concept-illustration_114360-4766.jpg"
                    alt="View Records">
                <h2>Class Attendance Records</h2>
                <p>View and manage your class attendance records</p>
                <a href="view_attendance.php">
                    <button id="viewRecordsBtn" class="btn primary">
                        <i class="fas fa-table"></i> View Class Records
                    </button>
                </a>
            </div>

            <div class="action-card">
                <img src="https://img.freepik.com/free-vector/teaching-concept-illustration_114360-1670.jpg"
                    alt="Class Management">
                <h2>Class Management</h2>
                <p>Manage your classes and student lists</p>
                <button id="manageClassesBtn" class="btn secondary" onclick="openModal()">
                    <i class="fas fa-chalkboard-teacher"></i> Manage Classes
                </button>
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-item">
                <img src="img/imgecce.webp" alt="UB Campus">
                <h3>Excellence in Education</h3>
                <p>Supporting UB's commitment to quality education since 1946</p>
            </div>
            <div class="feature-item">
                <img src="img/1-1-jpg.webp" alt="UB Innovation">
                <h3>Digital Innovation</h3>
                <p>Embracing technology for better educational management</p>
            </div>
            <div class="feature-item">
                <img src="img/UB-UBNITED-1-2048x1355.jpg" alt="UB Community">
                <h3>Strong Community</h3>
                <p>Building connections through efficient systems</p>
            </div>
        </div>
    </div>

    <!-- Subject Creation Modal -->
    <div id="subjectModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h4 class="mb-0"><i class="fas fa-book me-2"></i>Create Subject</h4>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="subject_code" class="form-label">Subject Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-code"></i></span>
                        <input type="text" id="subject_code" name="subject_code" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <input type="text" id="subject_name" name="subject_name" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-plus-circle me-2"></i>Create Subject
                </button>
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function openModal() {
            document.getElementById("subjectModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("subjectModal").style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == document.getElementById("subjectModal")) {
                closeModal();
            }
        }
    </script>
    <style>
        /* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    width: 90%;
    max-width: 450px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    padding: 2rem;
}

.close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    transition: color 0.2s ease;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    color: #800000;
    background: #f5f5f5;
}

h4.mb-0 {
    color: #800000;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

h4.mb-0 i {
    font-size: 1.25rem;
}

.mb-3 {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #444;
    font-weight: 500;
    font-size: 0.95rem;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-group-text {
    position: absolute;
    left: 1rem;
    color: #800000;
    z-index: 2;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.2s ease;
    background: #f8f8f8;
}

.form-control:focus {
    border-color: #800000;
    background: #fff;
    outline: none;
    box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.2s ease;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(45deg, #800000, #a00000);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(128, 0, 0, 0.3);
}

.w-100 {
    width: 100%;
}

.me-2 {
    margin-right: 0.5rem;
}

/* Animation */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translate(-50%, -48%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.modal-content {
    animation: modalFadeIn 0.3s ease forwards;
}

/* Responsive Design */
@media (max-width: 480px) {
    .modal-content {
        width: 95%;
        padding: 1.5rem;
    }

    h4.mb-0 {
        font-size: 1.25rem;
    }

    .form-control {
        font-size: 0.95rem;
    }
}
    </style>
</body>

</html>
