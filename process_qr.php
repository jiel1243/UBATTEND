<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if qr_data is set
    if (!isset($_POST['qr_data']) || !isset($_POST['subject'])) {
        echo json_encode(["success" => false, "message" => "Missing qr_data or subject."]);
        exit;
    }

    // Get QR data and subject from POST request
    $qr_data = $_POST["qr_data"] ?? '';
    $subject = $_POST["subject"] ?? '';
    
    // Decode the QR data (which should be JSON)
    $decoded_data = json_decode($qr_data, true);

    if (!isset($decoded_data["student_id"], $decoded_data["student_name"]) || !$subject) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid QR code data or missing subject."]);
        exit;
    }

    $student_id = $decoded_data["student_id"];
    $student_name = $decoded_data["student_name"];

    // Check if the subject exists in the subjects table
    $stmt = $conn->prepare("SELECT subject_code FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid subject selected."]);
        $stmt->close();
        exit;
    }

    // Check if the student has already been marked present for this subject
    $subject_table = 'attendance_' . strtolower($subject); // Example: 'attendance_ws101'
    $stmt = $conn->prepare("SELECT COUNT(*) FROM $subject_table WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($attendance_count);
    $stmt->fetch();
    $stmt->close();

    if ($attendance_count > 0) {
        echo json_encode(["success" => false, "message" => "Attendance already recorded."]);
        exit;
    }

    // Insert attendance record into the database for the selected subject
    $stmt = $conn->prepare("INSERT INTO $subject_table (student_id, student_name, status, entry_time) VALUES (?, ?, 'Present', NOW())");

    if ($stmt === false) {
        // Log any SQL preparation errors
        error_log("Error preparing statement: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Failed to prepare statement."]);
        exit;
    }

    $stmt->bind_param("ss", $student_id, $student_name);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Attendance recorded successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to record attendance."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
