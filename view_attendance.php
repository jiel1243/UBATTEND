<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects dynamically from the database
$subjects_query = "SELECT subject_code, subject_name FROM subjects";
$subjects_result = $conn->query($subjects_query);
$subjects = [];

if ($subjects_result->num_rows > 0) {
    // Store the subjects in the array
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[] = [
            'code' => $row['subject_code'],
            'name' => $row['subject_name']
        ];
    }
} else {
    echo "No subjects found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Records - University of Batangas</title>
    <link rel="stylesheet" href="view.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <img src="img/bglogo.png" alt="UB Logo" class="logo">
            <div class="header-text">
                <h1>University of Batangas</h1>
                <p>Student Information System - Attendance Records</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="content-box">
            <h2>View Attendance Records</h2>
            
            <!-- Subject Selection Form -->
            <form method="POST" action="" class="form-group">
                <label for="subject">Select Subject:</label>
                <select name="subject" id="subject" required>
                    <option value="" disabled selected>Choose a subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo strtolower($subject['code']); ?>">
                            <?php echo htmlspecialchars($subject['code']) . " - " . htmlspecialchars($subject['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">View Records</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject"])) {
                $subject = $_POST["subject"];
                $table_name = "attendance_" . strtolower($subject);  // Dynamically use the subject's table

                // Check if the table exists for the selected subject
                $result = $conn->query("SHOW TABLES LIKE '$table_name'");
                if ($result->num_rows > 0) {
                    // Fetch attendance records
                    $query = "SELECT student_id, student_name, status, entry_time FROM $table_name ORDER BY entry_time DESC";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        echo "<table>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                    <th>Entry Time</th>
                                </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['student_id']) . "</td>
                                    <td>" . htmlspecialchars($row['student_name']) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
                                    <td>" . htmlspecialchars($row['entry_time']) . "</td>
                                  </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No records found for this subject.</p>";
                    }
                } else {
                    echo "<p class='error'>Invalid subject selected or table does not exist.</p>";
                }
            }
            ?>
        </div>

        <!-- Quick Links Section -->
        <div class="quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Student Portal</a></li>
                <li><a href="#">Course Schedule</a></li>
                <li><a href="#">Academic Calendar</a></li>
                <li><a href="#">Faculty Directory</a></li>
                <li><a href="#">Library Resources</a></li>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>University of Batangas</p>
                <p>Hilltop, Batangas City</p>
                <p>Phone: (043) 722-0877</p>
                <p>Email: info@ub.edu.ph</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="#">About UB</a></p>
                <p><a href="#">Admissions</a></p>
                <p><a href="#">Academic Programs</a></p>
                <p><a href="#">Research</a></p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                    <a href="#">Instagram</a>
                    <a href="#">LinkedIn</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> University of Batangas. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
