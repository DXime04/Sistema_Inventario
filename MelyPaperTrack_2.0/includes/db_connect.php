<?php
// Archivo de conexión a la base de datos
$servername = "localhost"; // o el nombre del servidor
$username = "root";        // tu usuario de la base de datos
$password = "";            // tu contraseña de la base de datos
$dbname = "MelyPaperTrack_Data"; // nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
