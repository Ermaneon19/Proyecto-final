<?php
$host = 'localhost';        // Servidor de la base de datos
$dbname = 'user_management';// Nombre de la base de datos
$username = 'root';         // Nombre de usuario (por defecto en XAMPP es "root")
$password = '';             // Contraseña (por defecto en XAMPP es vacío)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos " . $e->getMessage());
}
?>
