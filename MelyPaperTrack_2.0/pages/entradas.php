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
    <title>Gestión de Entradas</title>
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
        <h1>Gestión de Entradas</h1>

        <!-- Formulario para agregar nueva entrada -->
        <h2>Agregar Nueva Entrada</h2>
        <form action="entradas.php" method="POST">
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

            <label for="cantidad_entrada">Cantidad:</label>
            <input type="number" id="cantidad_entrada" name="cantidad_entrada" required><br>

            <button type="submit" name="agregar_entrada">Agregar</button>
        </form>

        <?php
        // Lógica para insertar una nueva entrada
        if (isset($_POST['agregar_entrada'])) {
            $id_producto = $_POST['id_producto'];
            $cantidad_entrada = $_POST['cantidad_entrada'];

            $stmt = $conn->prepare("INSERT INTO entradas (id_producto, cantidad_entrada) VALUES (?, ?)");
            $stmt->bind_param("ii", $id_producto, $cantidad_entrada);
            if ($stmt->execute()) {
                echo "<p>Entrada agregada con éxito.</p>";
            } else {
                echo "<p>Error al agregar la entrada.</p>";
            }
            $stmt->close();
        }
        ?>

        <!-- Lista de entradas -->
        <h2>Historial de Entradas</h2>
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
                $query = "SELECT e.*, p.nombre_producto FROM entradas e JOIN productos p ON e.id_producto = p.id_producto";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_producto']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['cantidad_entrada']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_entrada']) . '</td>';
                    echo '<td>';
                    echo '<a href="editar_entrada.php?id=' . $row['id_entrada'] . '">Editar</a> | ';
                    echo '<a href="eliminar_entrada.php?id=' . $row['id_entrada'] . '" onclick="return confirm(\'¿Estás seguro de eliminar esta entrada?\')">Eliminar</a>';
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
