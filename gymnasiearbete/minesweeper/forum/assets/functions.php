<?php
// samma som functions2 bara för vanliga forum och inte för huvudforum
require_once '../../dbhs.php';

$hostname_forumtest = "localhost";
$database_forumtest = "Minesweeper";
$username_forumtest = "Minesweeper";
$password_forumtest = "Minesweeper";


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
    echo "You must be logged in to post a message.";
    header('Location: index.php'); 
} else {
    $username = $_SESSION['userid'];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['msg'])) {

    if (isset($_GET['id']) && is_numeric($_GET['id']) && intval($_GET['id']) > 0) {
        $id = intval($_GET['id']);
    } else {

        echo "Invalid parent ID.";
        exit;
    }




    $message = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    if (insertForumpost($id, $username, $message)) {
        echo '';
    } else {
        echo htmlspecialchars("Error posting message.");
    }
}
function insertForumpost($id, $username, $message) {
    global $forum;
    error_log($id);
    error_log("Inserting: ID = $id, Username = $username, Message = $message");

    try {
        $sql = "INSERT INTO forum (parent_id, username, msg) VALUES (:id, :username, :msg)";
        $stmt = $forum->prepare($sql);

        $stmt->bindValue(':id', $id);
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


function getForumPosts($id = null) {
    global $forum;

    try {

        $sql = "SELECT username, msg, tid FROM forum";

        if ($id !== null) {
            $sql .= " WHERE parent_id = :parent_id";
        }

        $sql .= " ORDER BY tid DESC";

        $stmt = $forum->prepare($sql);

        if ($id !== null) {
            $stmt->bindParam(':parent_id', $id, PDO::PARAM_INT);
        }

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