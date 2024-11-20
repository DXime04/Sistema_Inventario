<?php 
session_start(); 
include '../includes/db_connect.php'; 

// Procesar edición y eliminación antes de enviar contenido HTML
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'edit') {
        // Redirigir a un formulario de edición
        header('Location: editar_reserva.php?id=' . $id);
        exit;
    } elseif ($action == 'delete') {
        // Eliminar reserva
        $stmt = $conn->prepare("DELETE FROM reservas WHERE id_reserva = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Reserva eliminada con éxito.";
        } else {
            echo "Error al eliminar la reserva.";
        }
        $stmt->close();
        exit;
    }
}

// Procesar inserción de una nueva reserva
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $id_cliente = $_POST['id_cliente'];
    $cantidad_reservada = $_POST['cantidad_reservada'];
    $fecha_limite_reserva = $_POST['fecha_limite_reserva'];
    $id_estado = 2; // Valor por defecto para estado 'reservado'

    // Insertar la nueva reserva en la base de datos
    $stmt = $conn->prepare("INSERT INTO reservas (id_producto, id_cliente, cantidad_reservada, fecha_reserva, fecha_limite_reserva, id_estado) VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iiisi", $id_producto, $id_cliente, $cantidad_reservada, $fecha_limite_reserva, $id_estado);

    if ($stmt->execute()) {
        echo "<p>Reserva añadida con éxito.</p>";
    } else {
        echo "<p>Error al añadir la reserva: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/reservas.css">
    <title>Gestión de Reservas</title>
    <style>
        body {
            display: flex;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background-color: #f4f4f4;
            padding-top: 20px;
            margin-left: 0px;
        }

        .container {
            margin-left: 320px; /* Mismo ancho que el sidebar */
            padding: 35px;
            width: calc(100% - 250px); /* Ajustar el ancho disponible */
        }

        .welcome-content {
            text-align: center;
            margin-top: 50px;
        }

        .welcome-content img {
            max-width: 200px;
            height: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $("form").on("submit", function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de manera tradicional
            var formData = $(this).serialize(); // Obtiene los datos del formulario

            $.post("reservas.php", formData, function(response) {
                $("#message").html(response); // Muestra la respuesta del servidor
                $("form")[0].reset(); // Resetea el formulario
            });
        });
    });
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Gestión de Reservas</h1>
        
        <div id="message"></div> <!-- Mensajes de éxito o error -->

        <!-- Formulario para agregar una nueva reserva -->
        <form action="reservas.php" method="POST">
            <label for="id_producto">Producto:</label>
            <select id="id_producto" name="id_producto" required>
                <?php
                $query = "SELECT id_producto, nombre_producto FROM productos";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id_producto']) . "'>" . htmlspecialchars($row['nombre_producto']) . "</option>";
                }
                ?>
            </select><br>

            <label for="id_cliente">Cliente:</label>
            <select id="id_cliente" name="id_cliente" required>
                <?php
                $query = "SELECT id_cliente, nombre_cliente FROM clientes";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id_cliente']) . "'>" . htmlspecialchars($row['nombre_cliente']) . "</option>";
                }
                ?>
            </select><br>

            <label for="cantidad_reservada">Cantidad:</label>
            <input type="number" id="cantidad_reservada" name="cantidad_reservada" required><br>

            <label for="fecha_limite_reserva">Fecha Límite:</label>
            <input type="datetime-local" id="fecha_limite_reserva" name="fecha_limite_reserva" required><br>

            <button type="submit">Añadir reserva</button>
        </form>

        <h2>Lista de Reservas</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th>Cantidad</th>
                    <th>Fecha Reserva</th>
                    <th>Fecha Límite</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT reservas.*, productos.nombre_producto, clientes.nombre_cliente, estados_reservas.nombre_estado 
                          FROM reservas 
                          JOIN productos ON reservas.id_producto = productos.id_producto
                          JOIN clientes ON reservas.id_cliente = clientes.id_cliente
                          JOIN estados_reservas ON reservas.id_estado = estados_reservas.id_estado";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_producto']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre_cliente']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['cantidad_reservada']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_reserva']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_limite_reserva']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre_estado']) . '</td>';
                    echo '<td>';
                    echo '<a href="reservas.php?action=edit&id=' . $row['id_reserva'] . '">Editar</a> | ';
                    echo '<a href="reservas.php?action=delete&id=' . $row['id_reserva'] . '">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
