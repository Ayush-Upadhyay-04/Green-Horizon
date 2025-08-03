<?php
// Include the config file for database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username_or_Email = $_POST['Email'];
    $Password = $_POST['Password'];

    // Prepare SQL query to check if the user exists by username or Email
    $sql = "SELECT * FROM register WHERE (username = ? OR Email = ?) LIMIT 1";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $username_or_Email, $username_or_Email);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the Password
            if (Password_verify($Password, $user['Password'])) {
                // Start the session
                session_start();
                // Store user info in session
                $_SESSION['username'] = $user['username'];
                $_SESSION['Email'] = $user['Email'];

                echo "<script>alert('Login successful!');</script>";
                // Redirect to dashboard or another page
                header('Location: index.html');
                exit();
            } else {
                echo "<script>alert('Incorrect Password');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username or Email');</script>";
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