<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'comercio_electronico';
$username = 'root'; // Cambia según tu configuración
$password = ''; // Cambia según tu configuración


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>