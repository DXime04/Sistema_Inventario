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

        <!-- Mostrar mensajes -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p class='mensaje'>" . $_SESSION['mensaje'] . "</p>";
            unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
        }
        ?>

        <!-- Aquí va el resto de la tabla y formulario para las entradas -->
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
