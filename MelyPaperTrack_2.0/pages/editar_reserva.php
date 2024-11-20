<?php 
session_start(); 
include '../includes/db_connect.php'; 

header('Content-Type: text/html; charset=UTF-8');

// Verificar si se ha recibido un ID de reserva
if (!isset($_GET['id'])) {
    echo "<p class='error-message'>ID de reserva no proporcionado.</p>";
    exit;
}

$id_reserva = $_GET['id'];

// Obtener los datos actuales de la reserva
$query = "SELECT reservas.*, productos.nombre_producto, clientes.nombre_cliente, estados_reservas.nombre_estado 
          FROM reservas 
          JOIN productos ON reservas.id_producto = productos.id_producto
          JOIN clientes ON reservas.id_cliente = clientes.id_cliente
          JOIN estados_reservas ON reservas.id_estado = estados_reservas.id_estado
          WHERE reservas.id_reserva = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_reserva);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();

if (!$reserva) {
    echo "<p class='error-message'>Reserva no encontrada.</p>";
    exit;
}

// Procesar el formulario de edición
if (isset($_POST['submit'])) {
    $cantidad_reservada = $_POST['cantidad_reservada'];
    $fecha_limite_reserva = $_POST['fecha_limite_reserva'];
    $id_estado = $_POST['id_estado'];

    // Actualizar la reserva
    $update_query = "UPDATE reservas SET cantidad_reservada = ?, fecha_limite_reserva = ?, id_estado = ? WHERE id_reserva = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("isii", $cantidad_reservada, $fecha_limite_reserva, $id_estado, $id_reserva);
    if ($update_stmt->execute()) {
        echo "<p class='success-message'>Reserva actualizada con éxito.</p>";
    } else {
        echo "<p class='error-message'>Error al actualizar la reserva.</p>";
    }
    $update_stmt->close();
    exit;
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
    <title>Editar Reserva</title>
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
        $("form#edit-form").on("submit", function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de manera tradicional
            var formData = $(this).serialize(); // Obtiene los datos del formulario

            $.post("editar_reserva.php?id=<?php echo htmlspecialchars($id_reserva); ?>", formData, function(response) {
                $("#message").html(response); // Muestra la respuesta del servidor
            }).fail(function() {
                $("#message").html("<p class='error-message'>Error en la solicitud.</p>");
            });
        });
    });
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Editar Reserva</h1>

        <!-- Contenedor para mensajes de éxito o error -->
        <div id="message"></div>

        <!-- Formulario para editar la reserva -->
        <form id="edit-form" action="editar_reserva.php?id=<?php echo htmlspecialchars($id_reserva); ?>" method="POST">
            <label for="id_producto">Producto:</label>
            <input type="text" id="id_producto" value="<?php echo htmlspecialchars($reserva['nombre_producto']); ?>" readonly><br>

            <label for="id_cliente">Cliente:</label>
            <input type="text" id="id_cliente" value="<?php echo htmlspecialchars($reserva['nombre_cliente']); ?>" readonly><br>

            <label for="cantidad_reservada">Cantidad:</label>
            <input type="number" id="cantidad_reservada" name="cantidad_reservada" value="<?php echo htmlspecialchars($reserva['cantidad_reservada']); ?>" required><br>

            <label for="fecha_limite_reserva">Fecha Límite:</label>
            <input type="datetime-local" id="fecha_limite_reserva" name="fecha_limite_reserva" value="<?php echo htmlspecialchars($reserva['fecha_limite_reserva']); ?>" required><br>

            <label for="id_estado">Estado:</label>
            <select id="id_estado" name="id_estado" required>
                <?php
                $query = "SELECT id_estado, nombre_estado FROM estados_reservas";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id_estado'] == $reserva['id_estado']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['id_estado']) . "' $selected>" . htmlspecialchars($row['nombre_estado']) . "</option>";
                }
                ?>
            </select><br>

            <button type="submit" name="submit">Actualizar reserva</button>
        </form>

        <h2>Acciones</h2>
        <form action="reservas.php" method="POST">
            <input type="hidden" name="id_reserva" value="<?php echo htmlspecialchars($id_reserva); ?>">
            <button type="submit" name="confirmar">Confirmar Reserva</button>
            <button type="submit" name="anular">Anular Reserva</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
