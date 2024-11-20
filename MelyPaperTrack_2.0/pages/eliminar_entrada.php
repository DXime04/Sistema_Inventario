<?php 
session_start(); 
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id_entrada = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM entradas WHERE id_entrada = ?");
    $stmt->bind_param("i", $id_entrada);
    if ($stmt->execute()) {
        header("Location: entradas.php");
        exit();
    } else {
        echo "<p>Error al eliminar la entrada.</p>";
    }
    $stmt->close();
}
?>
