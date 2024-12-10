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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"]; // Get the role from the form

    // Validate input fields
    if (empty($student_id) || empty($student_name) || empty($email) || empty($username) || empty($password) || empty($role)) {
        http_response_code(400);
        echo "All fields are required.";
        exit;
    }

    // Check for duplicates (Student ID, Email, or Username)
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? OR email = ? OR username = ?");
    $stmt->bind_param("sss", $student_id, $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(400);
        echo "Student ID, Email, or Username already exists.";
        exit;
    }

    // Proceed to insert into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (student_id, student_name, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_id, $student_name, $email, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Registration successful! Redirecting to login...";
        echo "WELCOME!";
    } else {
        http_response_code(500);
        echo "Error: Could not complete registration.";
    }
    
    $stmt->close();
}

$conn->close();
?>
