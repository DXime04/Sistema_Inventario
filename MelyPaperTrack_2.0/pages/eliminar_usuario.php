<?php
session_start();
include '../includes/db_connect.php';

// Verificar si se ha recibido un ID de usuario
if (!isset($_GET['id'])) {
    header('Location: usuarios.php');
    exit;
}

$id_usuario = $_GET['id'];

// Preparar la consulta para eliminar el usuario
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    // Eliminar exitoso, redirigir a la página de usuarios con un mensaje de éxito
    $_SESSION['message'] = "Usuario eliminado con éxito.";
} else {
    // Error en la eliminación, redirigir a la página de usuarios con un mensaje de error
    $_SESSION['message'] = "Error al eliminar el usuario.";
}

$stmt->close();
$conn->close();

header('Location: usuarios.php');
exit;
?>
