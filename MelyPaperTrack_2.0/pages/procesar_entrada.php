<?php
session_start();
include '../includes/db_connect.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];

    // Verificar que el producto exista en la base de datos
    $stmt = $conn->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
    $stmt->bind_param("s", $nombre_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        $id_producto = $producto['id_producto'];

        // Insertar la nueva entrada en la tabla 'entradas'
        $stmt = $conn->prepare("INSERT INTO entradas (id_producto, cantidad_entrada, fecha_entrada) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $id_producto, $cantidad);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['mensaje'] = "Entrada registrada con éxito";
        } else {
            $_SESSION['mensaje'] = "Error al registrar la entrada";
        }
    } else {
        $_SESSION['mensaje'] = "Producto no encontrado";
    }

    $stmt->close();
    $conn->close();

    // Redirigir de nuevo a la página de gestión de entradas
    header('Location: gestion_entradas.php');
    exit();
}
?>
