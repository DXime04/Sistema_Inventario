<?php
session_start();
include '../includes/db_connect.php';

// Consultar los datos de los usuarios
$query = "
    SELECT usuarios.id_usuario, usuarios.nombre_usuario, usuarios.email_usuario, roles_usuarios.nombre_rol 
    FROM usuarios 
    JOIN roles_usuarios ON usuarios.id_rol = roles_usuarios.id_rol";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/usuarios.css">
    <title>Usuarios</title>
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
        <h1>Gestión de Usuarios</h1>

        <!-- Enlace para agregar un nuevo usuario -->
        <a href="agregar_usuario.php" class="btn-agregar">Agregar Usuario</a>

        <!-- Tabla para mostrar los usuarios -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_rol']); ?></td>
                        <td>
                            <!-- Enlace para editar el usuario -->
                            <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn-editar">Editar</a>
                            <!-- Enlace para eliminar el usuario (opcional) -->
                            <a href="eliminar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
