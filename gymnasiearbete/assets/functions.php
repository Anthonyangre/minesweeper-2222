<?php

function getScore(){
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");
    $stmt = $conn->prepare("SELECT * FROM score ORDER BY id DESC"); // Query to fetch user data
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
?>