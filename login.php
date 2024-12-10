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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user_id"];
    $password = $_POST["password"];

    // Fetch the user from the database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"])) {
            // Store user info in session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"]; // Store the user's role in the session

            // Redirect based on role
            if ($user["role"] == "student") {
                header("Location: generate_qr.php"); // Redirect to student dashboard
            } elseif ($user["role"] == "teacher") {
                header("Location: teacherdash.php"); // Redirect to teacher dashboard
            } elseif ($user["role"] == "admin") {
                header("Location: admindash.html"); // Redirect to admin dashboard
            } else {
                echo "Error: Invalid role.";
            }

            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>
