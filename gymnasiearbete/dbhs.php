<?php
//sql connection till våran sql server genom minesweeper usern

$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Kontrollera anslutningen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//starta en session 
session_start();
$errors = array();
//koden för när man registrerar,
if (isset($_POST["register"])) {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['name'])) {
        $errors[] = 'Fyll i fälten för användarnamn och lösenord';
    } else {$username = $_POST['username'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
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
        // söker databasen för username
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        // söker databasen för en email adress
        $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $mailres = $stmt->get_result();
        // om det finns en username eller email i databasen ger den en error
        if ($result ->num_rows > 0)  {
            $errors[] = "Denna användare finns redan";
        } elseif ($mailres ->num_rows > 0) {
            $errors[] = "Detta mejl finns redan";
        // om det inte finns några errors sätter den in username name, email, password( hashat ) i databasen
        } elseif (empty($errors)) { $zero = 0;
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
    //databas hanteraren till login.
if (isset($_POST['submit'])) {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors[] = 'Fyll i fälten för användarnamn och lösenord';
    } 
// sätter 2 variabler till username och passworden från formen på login sidan
    $username = $_POST['username'];
    $password = $_POST['password'];
// tar reda på på om det finns en username, password med samma
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
//om det finns en användare tar den resultatet och verifierar om lösenordet i korrekt jämnfört med den hashade på databasen
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            echo "Password is correct.<br>";
            $_SESSION['userid'] = $user['username'];
// tar poängen från databasen för att sätta sessionens poäng och vinster osv.
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
// koden för formen på profiländringssidan.
if (isset($_POST["Ändra"])) {
    // kollar om man är inloggad först
    if (!isset($_SESSION['userid'])) {
        $errors[] = "Du måste vara inloggad för att ändra dina uppgifter.";
    } else {
        // tar användarnamnet från sessionen
        $userid = $_SESSION['userid'];

        // tar datat som ska ändras från formen
        $password = $_POST['password3'];
        $name = $_POST['name3'];
        $email = $_POST['email3'];

        // validerar om det är en korrekt email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ogiltig e-postadress';
        }

        // validerar om namnet är korrekt gjord
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors[] = 'Namn får bara innehålla bokstäver och mellanslag.';
        }

        // validerar om lösenordet får användas
        $passwordhash = null; 
        // om lösenord är tomt ska man kunna ändra utan att ändra lösenord
        if (!empty($password)) {
            if (preg_match('/[^a-zA-Z0-9]/', $password)) {
                $errors[] = 'Lösenordet får inte innehålla specialtecken.';
            } else {
                $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            }
        }
// om det finns redan mail som används an en annan ska man inte kunna ändra till det mailet
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND username != ?");
        $stmt->bind_param("ss", $email, $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email används redan av en annan användare.";
        } else {
            // updatering av användaruppgifter
            if (empty($errors)) {
                if ($passwordhash) {
                    // uppdatera allt inkluderat lösenord
                    $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `email` = ?, `password` = ? WHERE `username` = ?");
                    $stmt->bind_param("ssss", $name, $email, $passwordhash, $userid);
                } else {
                    // updatering av allt utom lösenord
                    $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `email` = ? WHERE `username` = ?");
                    $stmt->bind_param("sss", $name, $email, $userid);
                }

                if ($stmt->execute()) {
                    // om det fungerar
            
                } else {
                    // om det inte fungerar
                    $errors[] = "Ett fel uppstod när uppgifterna skulle uppdateras.";
                }

                $stmt->close();
            }
        }
    }
}
$conn->close();
?>
