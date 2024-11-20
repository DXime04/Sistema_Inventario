<?php 
// Iniciar sesión antes de acceder a variables de sesión
session_start(); 
include '../includes/db_connect.php'; 

$id = $_GET['id'];

// Eliminar el proveedor
$stmt = $conn->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: proveedores.php");
} else {
    echo "<p class='error-message'>Error al eliminar el proveedor.</p>";
}
$stmt->close();
?>
