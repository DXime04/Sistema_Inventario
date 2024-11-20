<?php
session_start();
include '../includes/db_connect.php';

$id = $_GET['id'];

// Obtener los datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Procesar el formulario de edición
if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre_usuario'];
    $email = $_POST['email_usuario'];
    $password = $_POST['password_usuario'];

    // Actualizar el usuario en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, email_usuario = ?, password_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $nombre, $email, $password, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p class='success-message'>Usuario actualizado con éxito.</p>";
    } else {
        echo "<p class='error-message'>Error al actualizar el usuario.</p>";
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
    <link rel="stylesheet" href="../css/usuarios.css">
    <title>Editar Usuario</title>
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
        <h1>Editar Usuario</h1>

        <form action="editar_usuario.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required><br>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email_usuario" value="<?php echo htmlspecialchars($usuario['email_usuario']); ?>" required><br>

            <label for="password">Contraseña:</label>
            <input type="text" id="password" name="password_usuario" value="<?php echo htmlspecialchars($usuario['password_usuario']); ?>"><br>

            <button type="submit" name="submit">Actualizar Usuario</button>
            <a href="usuarios.php" class="btn-volver">Volver</a>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
