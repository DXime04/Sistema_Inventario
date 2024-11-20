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
    <title>Bienvenido a MelyPaperTrack</title>
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
            margin-left: 250px; /* Mismo ancho que el sidebar */
            padding: 20px;
            width: calc(100% - 250px); /* Ajustar el ancho disponible */
        }

        .welcome-content {
            text-align: center;
            margin-top: 50px;
        }

        .welcome-content img {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar fixed header -->
    <?php include '../includes/header.php'; ?>

    <!-- Contenido Principal -->
    <div class="container">
        <h1>Bienvenido a MelyPaperTrack</h1>

        <!-- Mensaje de bienvenida y logo centrado -->
        <div class="welcome-content">
            <img src="../img/logo.png" alt="Logo MelyPaperTrack">
            <?php if (isset($_SESSION['user'])): ?>
                <h2>¡Bienvenido a la plataforma de gestión de inventario, <?php echo $_SESSION['user']; ?>!</h2>
            <?php else: ?>
                <h2>¡Bienvenido a la plataforma de gestión de inventario!</h2>
                <p>Por favor, inicia sesión para continuar.</p>
            <?php endif; ?>
            <p>Aquí podrás gestionar tu inventario de manera eficiente.</p>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
