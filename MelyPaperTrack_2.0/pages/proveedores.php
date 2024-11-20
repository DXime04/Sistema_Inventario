<?php 
// Iniciar sesión antes de acceder a variables de sesión
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
    <link rel="stylesheet" href="../css/proveedores.css">
    <title>Gestión de Proveedores</title>
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
        <h1>Gestión de Proveedores</h1>

        <a href="agregar_proveedor.php" class="btn-agregar">Agregar Proveedor</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM proveedores");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id_proveedor']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre_proveedor']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['contacto_proveedor']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['telefono_proveedor']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['direccion_proveedor']) . '</td>';
                    echo '<td>
                            <a href="editar_proveedor.php?id=' . htmlspecialchars($row['id_proveedor']) . '" class="btn-editar">Editar</a>
                            <a href="eliminar_proveedor.php?id=' . htmlspecialchars($row['id_proveedor']) . '" class="btn-eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este proveedor?\');">Eliminar</a>
                          </td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
