<?php
// Include the config file for database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL query to insert username, email, and password
    $sql = "INSERT INTO register (Username, Email, Password) VALUES (?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!');</script>";
            // Redirect to login or another page if needed
            header('Location: index1.html');
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare SQL statement');</script>";
    }
}

// Close the database connection
$conn->close();
?>