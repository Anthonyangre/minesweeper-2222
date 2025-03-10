<?php
require_once '../../dbhs.php';

$hostname_forumtest = "localhost";
$database_forumtest = "Minesweeper";
$username_forumtest = "Minesweeper";
$password_forumtest = "Minesweeper";

// skapar en connection
try {
  $forum = new PDO("mysql:host=$hostname_forumtest;dbname=$database_forumtest", $username_forumtest, $password_forumtest);

  $forum->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['userid'])) {

    header('Location: index.php'); 
} else {
    $username = $_SESSION['userid'];
}




// om man skapar ett forum kollar den om titeln existerar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title'])) {
    $title = trim($_POST['title']); 
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ;

    if (insertporumpost($username, $title)) {
        echo '';
    } else {
        echo htmlspecialchars("Fel vid insättning av titeln."); 
    }
}

//funktionen för att skapa ett huvudforum, likadan som vanliga funktionen bara variablerna är annorlunda
function insertporumpost($username, $title) {
    global $forum;

    try {
        $sql = "INSERT INTO pforum (username, title) VALUES (:username, :title)";
        $stmt = $forum->prepare($sql);

        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':title', $title);

        if ($stmt->execute()) {
            error_log("title successfully inserted: $title");
            return true;
        } else {
            error_log("Failed to insert title: $title");
            return false;
        }
    } catch (Exception $e) {
        
        error_log("Error inserting message: " . $e->getMessage());
        return false;
    }
}

// funktionen för att hämta huvudforum
function getporumPosts() {
    global $forum;

    try {
        $sql = "SELECT username, title, id FROM pforum ORDER BY title DESC";
        $stmt = $forum->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

$records2 = getporumPosts();
if (isset($_POST["logga_ut"])) { 
    session_unset();

    session_destroy();
    header('Location: index.php'); 
    
}
?>