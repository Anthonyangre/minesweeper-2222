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
    } else {$username = $_POST['username'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ogiltig e-postadress. En giltig e-postadress måste innehålla ett @-tecken och en domän.';
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $errors[] = 'Användarnamn får bara innehålla bokstäver och siffror.';
        }

      
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors[] = 'Namn får bara innehålla bokstäver och mellanslag.';
        }

    
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'Lösenordet får inte innehålla specialtecken.';
        }
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $mailres = $stmt->get_result();
        
        if ($result ->num_rows > 0)  {
            $errors[] = "Denna användare finns redan";
        
        } elseif ($mailres ->num_rows > 0)  {
            $errors[] = "Detta mejl finns redan";
        } else  { 
            if (empty($errors)) { $zero = 0;
            $passwordh = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO `users`(`username`,`email`,`name`, `password`) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $username, $email, $name, $passwordh);
            $stmt->execute();
         
            $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
             $user = $result->fetch_assoc();
             $stmt->close();
            $_SESSION['userid'] = $user['username'];
            $username = $_POST['username'];
            
            $stmt = $conn->prepare("INSERT INTO `score`(`username`,`points`,`wins`, `lose`) VALUES (?,?,?,?)");
            $stmt->bind_param("siii", $username, $zero, $zero, $zero);
            $stmt->execute();
            $stmt->close();
            $_SESSION['wins'] = $zero;
            $_SESSION['lose'] = $zero;
            $_SESSION['points'] = $zero;
            header('Location: ./minesweeper/pre_game_choice.php');
            
            } 
        }
            
    }
    
}
if (isset($_POST['submit'])) {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors[] = 'Fyll i fälten för användarnamn och lösenord';
    } 

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            echo "Password is correct.<br>";
            $_SESSION['userid'] = $user['username'];

            $stmt = $conn->prepare("SELECT points, wins, lose FROM score WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $score = $result->fetch_assoc();
            $_SESSION['wins'] = $score['wins'];
            $_SESSION['lose'] = $score['lose'];
            $_SESSION['points'] = $score['points'];
            
            header('Location: ./minesweeper/pre_game_choice.php');
            
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
if (isset($_POST["Ändra"])) {
    // Check if the user is logged in
    if (!isset($_SESSION['userid'])) {
        $errors[] = "Du måste vara inloggad för att ändra dina uppgifter.";
    } else {
        // Retrieve current logged-in user's username (from the session)
        $userid = $_SESSION['userid'];

        // Fetch the data from the form
        $password = $_POST['password3'];
        $name = $_POST['name3'];
        $email = $_POST['email3'];

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ogiltig e-postadress';
        }

        // Validate name
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors[] = 'Namn får bara innehålla bokstäver och mellanslag.';
        }

        // Validate password if provided
        $passwordhash = null; // Default to null
        if (!empty($password)) {
            if (preg_match('/[^a-zA-Z0-9]/', $password)) {
                $errors[] = 'Lösenordet får inte innehålla specialtecken.';
            } else {
                $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        // Check if the new email already exists (exclude the current user)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND username != ?");
        $stmt->bind_param("ss", $email, $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email används redan av en annan användare.";
        } else {
            // Update the user details
            if (empty($errors)) {
                if ($passwordhash) {
                    // Update all fields including password
                    $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `email` = ?, `password` = ? WHERE `username` = ?");
                    $stmt->bind_param("ssss", $name, $email, $passwordhash, $userid);
                } else {
                    // Update fields excluding password
                    $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `email` = ? WHERE `username` = ?");
                    $stmt->bind_param("sss", $name, $email, $userid);
                }

                if ($stmt->execute()) {
                    echo "Dina uppgifter har uppdaterats.";
                } else {
                    $errors[] = "Ett fel uppstod när uppgifterna skulle uppdateras.";
                }

                $stmt->close();
            }
        }
    }
}
$conn->close();
?>
