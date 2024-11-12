<?php
$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Kontrollera anslutningen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$errors = array();
if (isset($_POST["register"])) {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['name'])) {
        $errors[] = 'Fyll i fälten för användarnamn och lösenord';
    } 
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result ->num_rows > 0) {
        $errors[] = "denna användare finns redan";
    
    } else {
    $passwordh = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO `users`(`username`,`email`,`name`, `password`) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $username, $email, $name, $passwordh);
    $stmt->execute();
    $stmt->close();
    $_SESSION['userid'] = $user['id'];
    header('Location: ./minesweeper/index.php');
    }
}
if (isset($_POST['submit'])) {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors[] = 'Fyll i fälten för användarnamn och lösenord';
    } 

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            echo "Password is correct.<br>";
            $_SESSION['userid'] = $user['id'];
            header('Location: ./minesweeper/index.php');
            
            $_SESSION['logged_in'] = true;
            exit;
        } else {
            
            $errors[] = 'Kontrollera lösenord och användarnamn';
        }
    } else {
      
        $errors[] = 'Kontrollera lösenord och användarnamn';
    }

    $stmt->close();
}

$conn->close();
?>
