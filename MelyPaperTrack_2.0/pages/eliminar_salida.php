<?php 
session_start(); 
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id_salida = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM salidas WHERE id_salida = ?");
    $stmt->bind_param("i", $id_salida);
    if ($stmt->execute()) {
        header("Location: salidas.php");
        exit();
    } else {
        echo "<p>Error al eliminar la salida.</p>";
    }
    $stmt->close();
}
?>
