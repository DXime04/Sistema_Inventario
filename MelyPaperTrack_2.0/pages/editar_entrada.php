<?php 
session_start(); 
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id_entrada = $_GET['id'];
    $result = $conn->query("SELECT * FROM entradas WHERE id_entrada = $id_entrada");
    $entrada = $result->fetch_assoc();
}

if (isset($_POST['editar_entrada'])) {
    $id_entrada = $_POST['id_entrada'];
    $id_producto = $_POST['id_producto'];
    $cantidad_entrada = $_POST['cantidad_entrada'];

    $stmt = $conn->prepare("UPDATE entradas SET id_producto = ?, cantidad_entrada = ? WHERE id_entrada = ?");
    $stmt->bind_param("iii", $id_producto, $cantidad_entrada, $id_entrada);
    if ($stmt->execute()) {
        header("Location: entradas.php");
        exit();
    } else {
        echo "<p>Error al editar la entrada.</p>";
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
    <title>Editar Entrada</title>
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
        <h1>Editar Entrada</h1>
        <form action="editar_entrada.php" method="POST">
            <input type="hidden" name="id_entrada" value="<?php echo htmlspecialchars($entrada['id_entrada']); ?>">
            
            <label for="producto">Producto:</label>
            <select id="producto" name="id_producto" required>
                <?php
                $query = "SELECT id_producto, nombre_producto FROM productos";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id_producto'] == $entrada['id_producto']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['id_producto']) . "' $selected>" . htmlspecialchars($row['nombre_producto']) . "</option>";
                }
                ?>
            </select><br>

            <label for="cantidad_entrada">Cantidad:</label>
            <input type="number" id="cantidad_entrada" name="cantidad_entrada" value="<?php echo htmlspecialchars($entrada['cantidad_entrada']); ?>" required><br>

            <button type="submit" name="editar_entrada">Guardar Cambios</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
