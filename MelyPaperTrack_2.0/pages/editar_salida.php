<?php 
session_start(); 
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id_salida = $_GET['id'];
    $result = $conn->query("SELECT * FROM salidas WHERE id_salida = $id_salida");
    $salida = $result->fetch_assoc();
}

if (isset($_POST['editar_salida'])) {
    $id_salida = $_POST['id_salida'];
    $id_producto = $_POST['id_producto'];
    $cantidad_salida = $_POST['cantidad_salida'];

    $stmt = $conn->prepare("UPDATE salidas SET id_producto = ?, cantidad_salida = ? WHERE id_salida = ?");
    $stmt->bind_param("iii", $id_producto, $cantidad_salida, $id_salida);
    if ($stmt->execute()) {
        header("Location: salidas.php");
        exit();
    } else {
        echo "<p>Error al editar la salida.</p>";
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
    <link rel="stylesheet" href="../css/entradas_salidas.css">
    <title>Editar Salida</title>
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
        <h1>Editar Salida</h1>
        <form action="editar_salida.php" method="POST">
            <input type="hidden" name="id_salida" value="<?php echo htmlspecialchars($salida['id_salida']); ?>">
            
            <label for="producto">Producto:</label>
            <select id="producto" name="id_producto" required>
                <?php
                $query = "SELECT id_producto, nombre_producto FROM productos";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id_producto'] == $salida['id_producto']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['id_producto']) . "' $selected>" . htmlspecialchars($row['nombre_producto']) . "</option>";
                }
                ?>
            </select><br>

            <label for="cantidad_salida">Cantidad:</label>
            <input type="number" id="cantidad_salida" name="cantidad_salida" value="<?php echo htmlspecialchars($salida['cantidad_salida']); ?>" required><br>

            <button type="submit" name="editar_salida">Guardar Cambios</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
