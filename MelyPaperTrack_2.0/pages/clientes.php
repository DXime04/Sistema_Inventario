<?php 
// Iniciar sesión antes de acceder a variables de sesión
session_start(); 
include '../includes/db_connect.php'; 

// Iniciar el buffer de salida para evitar problemas con header()
ob_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <title>Gestión de Clientes</title>
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
        <h1>Gestión de Clientes</h1>

        <?php
        // Verificar si se ha enviado el formulario para agregar un cliente
        if (isset($_POST['submit'])) {
            $nombre_cliente = $_POST['nombre_cliente'];
            $contacto_cliente = $_POST['contacto_cliente'];
            $email_cliente = $_POST['email_cliente'];
            $telefono_cliente = $_POST['telefono_cliente'];

            // Preparar y ejecutar la consulta de inserción
            $stmt = $conn->prepare("INSERT INTO clientes (nombre_cliente, contacto_cliente, email_cliente, telefono_cliente) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre_cliente, $contacto_cliente, $email_cliente, $telefono_cliente);
            if ($stmt->execute()) {
                echo "<p class='success-message'>Cliente añadido con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al añadir el cliente.</p>";
            }
            $stmt->close();
        }
        ?>

        <!-- Formulario para agregar un nuevo cliente -->
        <form action="clientes.php" method="POST">
            <label for="nombre_cliente">Nombre:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" required><br>

            <label for="contacto_cliente">Contacto:</label>
            <input type="text" id="contacto_cliente" name="contacto_cliente" required><br>

            <label for="email_cliente">Email:</label>
            <input type="email" id="email_cliente" name="email_cliente"><br>

            <label for="telefono_cliente">Teléfono:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente"><br>

            <button type="submit" name="submit">Añadir cliente</button>
        </form>

        <h2>Lista de Clientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM clientes");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nombre_cliente']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['contacto_cliente']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email_cliente']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['telefono_cliente']) . '</td>';
                    echo '<td>';
                    echo '<a href="clientes.php?action=edit&id=' . $row['id_cliente'] . '">Editar</a> | ';
                    echo '<a href="clientes.php?action=delete&id=' . $row['id_cliente'] . '">Eliminar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <?php
        // Procesar edición y eliminación
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            $id = $_GET['id'];

            if ($action == 'edit') {
                // Redirigir a la página de edición antes de que se envíe cualquier salida
                header('Location: editar_cliente.php?id=' . $id);
                exit;
            } elseif ($action == 'delete') {
                // Eliminar cliente
                $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<p class='success-message'>Cliente eliminado con éxito.</p>";
                } else {
                    echo "<p class='error-message'>Error al eliminar el cliente.</p>";
                }
                $stmt->close();
            }
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>

    <?php
    // Finalizar el buffer de salida y enviar todo al navegador
    ob_end_flush(); 
    ?>
</body>
</html>
