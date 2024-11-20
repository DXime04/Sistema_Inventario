<?php 
session_start(); 
include '../includes/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $contacto_cliente = $_POST['contacto_cliente'];
    $email_cliente = $_POST['email_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];

    // Actualizar cliente
    $stmt = $conn->prepare("UPDATE clientes SET nombre_cliente = ?, contacto_cliente = ?, email_cliente = ?, telefono_cliente = ? WHERE id_cliente = ?");
    $stmt->bind_param("ssssi", $nombre_cliente, $contacto_cliente, $email_cliente, $telefono_cliente, $id_cliente);

    if ($stmt->execute()) {
        echo "<p class='success-message'>Cliente actualizado con éxito.</p>";
    } else {
        echo "<p class='error-message'>Error al actualizar el cliente.</p>";
    }
    $stmt->close();
}
?>
