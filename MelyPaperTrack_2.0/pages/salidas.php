<?php 
session_start(); 
include '../includes/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/entradas_salidas.css">
    <title>Gestión de Salidas</title>
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
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Gestión de Salidas</h1>

        <!-- Formulario para agregar nueva salida -->
        <h2>Agregar Nueva Salida</h2>
        <form action="salidas.php" method="POST">
            <label for="producto">Producto:</label>
            <select id="producto" name="id_producto" required>
                <?php
                $query = "SELECT id_producto, nombre_producto FROM productos";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id_producto']) . "'>" . htmlspecialchars($row['nombre_producto']) . "</option>";
                }
                ?>
            </select><br>

            <label for="cantidad_salida">Cantidad:</label>
            <input type="number" id="cantidad_salida" name="cantidad_salida" required><br>

            <button type="submit" name="agregar_salida">Agregar</button>
        </form>

        <?php
        // Lógica para insertar una nueva salida
        if (isset($_POST['agregar_salida'])) {
            $id_producto = $_POST['id_producto'];
            $cantidad_salida = $_POST['cantidad_salida'];

            $stmt = $conn->prepare("INSERT INTO salidas (id_producto, cantidad_salida) VALUES (?, ?)");
            $stmt->bind_param("ii", $id_producto, $cantidad_salida);
            if ($stmt->execute()) {
                echo "<p>Salida agregada con éxito.</p>";
            } else {
                echo "<p>Error al agregar la salida.</p>";
            }
            $stmt->close();
        }
        ?>

        <!-- Lista de salidas -->
        <h2>Historial de Salidas</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT s.*, p.nombre_producto FROM salidas s JOIN productos p ON s.id_producto = p.id_producto";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_producto']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['cantidad_salida']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_salida']) . '</td>';
                    echo '<td>';
                    echo '<a href="editar_salida.php?id=' . $row['id_salida'] . '">Editar</a> | ';
                    echo '<a href="eliminar_salida.php?id=' . $row['id_salida'] . '" onclick="return confirm(\'¿Estás seguro de eliminar esta salida?\')">Eliminar</a>';
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
