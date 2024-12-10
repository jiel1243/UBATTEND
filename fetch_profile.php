<?php
header('Content-Type: application/json');
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the user_id from the request
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

// Fetch user profile from the database
$stmt = $conn->prepare("SELECT student_id, student_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $student_id = $user["student_id"];
    $student_name = $user["student_name"];
} else {
    echo json_encode(["success" => false, "message" => "User not found."]);
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
    echo json_encode(["success" => false, "message" => "No subjects found."]);
    exit;
}

// Close the connection after all queries are done
$conn->close();

echo json_encode([
    "success" => true,
    "student_id" => $student_id,
    "student_name" => $student_name,
    "subjects" => $subjects
]);
?>
