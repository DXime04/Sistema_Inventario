<?php
session_start();
include '../includes/db_connect.php';

// Procesar la adición de un nuevo usuario
if (isset($_POST['submit'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $password_usuario = $_POST['password_usuario'];
    $id_rol = $_POST['id_rol'];

    $stmt = $conn->prepare("
        INSERT INTO usuarios (nombre_usuario, email_usuario, password_usuario, id_rol) 
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre_usuario, $email_usuario, $password_usuario, $id_rol);

    if ($stmt->execute()) {
        $message = "<p class='success-message'>Usuario agregado con éxito.</p>";
    } else {
        $message = "<p class='error-message'>Error al agregar el usuario.</p>";
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
    <title>Agregar Usuario</title>
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
        <h1>Agregar Usuario</h1>

        <!-- Contenedor para mensajes de éxito o error -->
        <div id="message"><?php if (isset($message)) echo $message; ?></div>

        <form method="POST">
            <label for="nombre_usuario">Nombre de usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required><br>

            <label for="email_usuario">Email:</label>
            <input type="email" id="email_usuario" name="email_usuario" required><br>

            <label for="password_usuario">Contraseña:</label>
            <input type="password" id="password_usuario" name="password_usuario" required><br>

            <label for="id_rol">Rol:</label>
            <select id="id_rol" name="id_rol" required>
                <?php
                $query = "SELECT id_rol, nombre_rol FROM roles_usuarios";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id_rol']) . "'>" . htmlspecialchars($row['nombre_rol']) . "</option>";
                }
                ?>
            </select><br>

            <button type="submit" name="submit">Agregar Usuario</button>
            <a href="usuarios.php" class="btn-volver">Volver</a>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
