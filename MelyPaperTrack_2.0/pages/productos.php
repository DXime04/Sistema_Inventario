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
    <link rel="stylesheet" href="../css/productos.css">
    <title>Gestión de Productos</title>
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
        <h1>Gestión de Productos</h1>

        <a href="agregar_producto.php" class="btn-agregar">Agregar Producto</a>

        <h2>Lista de Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT p.*, c.nombre_categoria, pr.nombre_proveedor FROM productos p JOIN categorias c ON p.id_categoria = c.id_categoria JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_producto']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['descripcion']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['cantidad_stock']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['precio_compra']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['precio_venta']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre_categoria']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre_proveedor']) . '</td>';

                    // Verificar si 'foto' existe en $row antes de usarlo
                    if (isset($row['foto']) && !empty($row['foto'])) {
                        // Si 'foto' está almacenado como una ruta de archivo
                        if (filter_var($row['foto'], FILTER_VALIDATE_URL) || file_exists($row['foto'])) {
                            echo '<td><img src="' . htmlspecialchars($row['foto']) . '" width="100" /></td>';
                        } else {
                            // Si 'foto' es un BLOB en la base de datos
                            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['foto']) . '" width="100" /></td>';
                        }
                    } else {
                        echo '<td>No disponible</td>';
                    }

                    echo '<td>';
                    echo '<a href="editar_producto.php?id=' . $row['id_producto'] . '" class="btn-editar">Editar</a> | ';
                    echo '<a href="productos.php?action=delete&id=' . $row['id_producto'] . '" class="btn-eliminar" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este producto?\');">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Eliminar el producto
            $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: productos.php");
                exit();
            } else {
                echo "<p class='error-message'>Error al eliminar el producto.</p>";
            }
            $stmt->close();
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
