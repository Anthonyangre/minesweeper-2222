<?php
//sql connection till våran sql server genom minesweeper usern

$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];

    // Query to fetch user data
    $stmt = $conn->prepare("SELECT username, name, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $name = $user['name'];
        $email = $user['email'];
    } else {
        $errors[] = "Användardata kunde inte hämtas.";
    }
    $stmt->close();
} else {
    $errors[] = "Du måste vara inloggad för att ändra dina uppgifter";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $uploadDir = 'uploads/'; // Directory to save uploaded files

    // Check if the uploads directory exists, if not create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the directory with proper permissions
    }

    // Check if a file was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_picture'];

        // Validate the file type (only allow images)
        $allowedTypes = ['image/jpeg'];
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG files are allowed.";
        }

        // Validate file size (e.g., max 2MB)
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxFileSize) {
            $errors[] = "The file is too large. Maximum size is 2MB.";
        }

        if (empty($errors)) {
            // Get the file extension
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$_SESSION["userid"] = $username;
            // Create a filename using the username
            $uniqueFilename = $username . "_picture." . $fileExtension;

            // Define the full path to save the file
            $profilePicturePath = $uploadDir . $uniqueFilename;

            // Move the uploaded file to the upload directory
            if (move_uploaded_file($file['tmp_name'], $profilePicturePath)) {
            } else {
                $errors[] = "Failed to move the uploaded file.";
            }
        }
    } else {
        
    }


}

?>