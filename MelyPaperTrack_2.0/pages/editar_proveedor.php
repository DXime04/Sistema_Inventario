<?php 
// Iniciar sesión antes de acceder a variables de sesión
session_start(); 
include '../includes/db_connect.php'; 

$id = $_GET['id'];

// Obtener los datos del proveedor
$stmt = $conn->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();
$stmt->close();

if (!$provider) {
    die("Proveedor no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/proveedores.css">
    <title>Editar Proveedor</title>
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
        <h1>Editar Proveedor</h1>

        <form action="editar_proveedor.php?id=<?php echo $id; ?>" method="POST">
            <label for="nombre">Nombre del proveedor:</label>
            <input type="text" id="nombre" name="nombre_proveedor" value="<?php echo htmlspecialchars($provider['nombre_proveedor']); ?>" required>

            <label for="contacto">Contacto:</label>
            <input type="text" id="contacto" name="contacto_proveedor" value="<?php echo htmlspecialchars($provider['contacto_proveedor']); ?>">

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono_proveedor" value="<?php echo htmlspecialchars($provider['telefono_proveedor']); ?>">

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion_proveedor" value="<?php echo htmlspecialchars($provider['direccion_proveedor']); ?>">

            <button type="submit" name="update">Actualizar Proveedor</button>
            <a href="proveedores.php" class="btn-volver">Volver</a>
        </form>

        <?php
        if (isset($_POST['update'])) {
            $nombre = $_POST['nombre_proveedor'];
            $contacto = $_POST['contacto_proveedor'];
            $telefono = $_POST['telefono_proveedor'];
            $direccion = $_POST['direccion_proveedor'];

            // Actualizar el proveedor en la base de datos
            $stmt = $conn->prepare("UPDATE proveedores SET nombre_proveedor = ?, contacto_proveedor = ?, telefono_proveedor = ?, direccion_proveedor = ? WHERE id_proveedor = ?");
            $stmt->bind_param("ssssi", $nombre, $contacto, $telefono, $direccion, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<p class='success-message'>Proveedor actualizado con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al actualizar el proveedor.</p>";
            }
            $stmt->close();
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
