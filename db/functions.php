<?php
include('db_connection.php');

function getInterests($pdo) {
    $stmt = $pdo->prepare("SELECT id, name FROM interests");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>