<?php
require_once '../../dbhs.php';

$hostname_forumtest = "localhost";
$database_forumtest = "Minesweeper";
$username_forumtest = "Minesweeper";
$password_forumtest = "Minesweeper";


try {
  $forum = new PDO("mysql:host=$hostname_forumtest;dbname=$database_forumtest", $username_forumtest, $password_forumtest);
  // set the PDO error mode to exception
  $forum->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['userid'])) {
    echo "You must be logged in to post a message.";
    header('Location: index.php'); 
} else {
    $username = $_SESSION['userid'];
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['msg'])) {
    $message = trim($_POST['msg']); 
    $message = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ;

    if (insertForumpost($username, $message)) {
        echo '';
    } else {
        echo htmlspecialchars("Fel vid insättning av inlägget."); 
    }
}

function insertForumpost($username, $message) {
    global $forum;

    try {
        $sql = "INSERT INTO forum (username, msg) VALUES (:username, :msg)";
        $stmt = $forum->prepare($sql);

        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':msg', $message);

        if ($stmt->execute()) {
            error_log("Message successfully inserted: $message");
            return true;
        } else {
            error_log("Failed to insert message: $message");
            return false;
        }
    } catch (Exception $e) {
        
        error_log("Error inserting message: " . $e->getMessage());
        return false;
    }
}



function getForumPosts() {
    global $forum;

    try {
        $sql = "SELECT username, msg, tid FROM forum ORDER BY tid DESC";
        $stmt = $forum->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

$records = getForumPosts();
if (isset($_POST["logga_ut"])) { 
    session_unset();

    session_destroy();
    header('Location: index.php'); 
    
}
?>