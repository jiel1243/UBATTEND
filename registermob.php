<?php
// Database connection
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the incoming JSON data
$data = json_decode(file_get_contents("php://input"));

// Validate if the necessary data exists
if (!isset($data->student_id) || !isset($data->student_name) || !isset($data->email) || !isset($data->username) || !isset($data->password) || !isset($data->role)) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required."]);
    exit;
}

// Assign variables from incoming JSON data
$student_id = $data->student_id;
$student_name = $data->student_name;
$email = $data->email;
$username = $data->username;
$password = $data->password;
$role = $data->role;

// Check for duplicates (Student ID, Email, or Username)
$stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? OR email = ? OR username = ?");
$stmt->bind_param("sss", $student_id, $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(["error" => "Student ID, Email, or Username already exists."]);
    exit;
}

// Proceed to insert into the database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (student_id, student_name, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $student_id, $student_name, $email, $username, $hashed_password, $role);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["success" => "Registration successful!"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error: Could not complete registration. " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
