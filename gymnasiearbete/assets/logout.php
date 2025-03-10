<?php
// rensar all session data och sedan skickar en till start sidan.
session_start();

session_unset();

session_destroy();

header('Location: ../index.php'); 
exit;
?>