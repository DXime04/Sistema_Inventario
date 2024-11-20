<?php 
// Iniciar sesión antes de acceder a variables de sesión
session_start(); 
include '../includes/db_connect.php'; 

// Procesar edición y eliminación antes de enviar cualquier salida
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'edit') {
        // Redirigir al formulario de edición
        header('Location: editar_categoria.php?id=' . $id);
        exit;
    } elseif ($action == 'delete') {
        // Eliminar categoría
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<p class='success-message'>Categoría eliminada con éxito.</p>";
        } else {
            echo "<p class='error-message'>Error al eliminar la categoría.</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/categorias.css">
    <title>Gestión de Categorías</title>
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
        <h1>Gestión de Categorías</h1>

        <form action="categorias.php" method="POST">
            <!-- Formulario para agregar una nueva categoría -->
            <label for="nombre_categoria">Nombre de la categoría:</label>
            <input type="text" id="nombre_categoria" name="nombre_categoria" required>
            <button type="submit" name="submit">Añadir categoría</button>
        </form>
        <?php
        // Verificar si se ha enviado el formulario para agregar una categoría
        if (isset($_POST['submit'])) {
            $nombre_categoria = $_POST['nombre_categoria'];

            // Preparar y ejecutar la consulta de inserción
            $stmt = $conn->prepare("INSERT INTO categorias (nombre_categoria) VALUES (?)");
            $stmt->bind_param("s", $nombre_categoria);
            if ($stmt->execute()) {
                echo "<p class='success-message'>Categoría añadida con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al añadir la categoría.</p>";
            }
            $stmt->close();
        }
        ?>

        <h2>Lista de Categorías</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM categorias");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_categoria']) . '</td>';
                    echo '<td>';
                    echo '<a href="categorias.php?action=edit&id=' . $row['id_categoria'] . '">Editar</a> | ';
                    echo '<a href="categorias.php?action=delete&id=' . $row['id_categoria'] . '">Eliminar</a>';
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
