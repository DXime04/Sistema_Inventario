<?php 
// Iniciar sesión antes de acceder a variables de sesión
session_start(); 
include '../includes/db_connect.php'; 

$id = $_GET['id'];

// Obtener los datos de la categoría
$stmt = $conn->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

if (!$category) {
    die("Categoría no encontrada.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/categorias.css">
    <title>Editar Categoría</title>
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
        <h1>Editar Categoría</h1>

        <form action="editar_categoria.php?id=<?php echo $id; ?>" method="POST">
            <!-- Formulario para editar una categoría -->
            <label for="nombre_categoria">Nombre de la categoría:</label>
            <input type="text" id="nombre_categoria" name="nombre_categoria" value="<?php echo htmlspecialchars($category['nombre_categoria']); ?>" required>
            <button type="submit" name="update">Actualizar categoría</button>
            <a href="categorias.php" class="btn-volver">Volver</a>
        </form>

        <?php
        if (isset($_POST['update'])) {
            $nombre_categoria = $_POST['nombre_categoria'];

            // Actualizar la categoría en la base de datos
            $stmt = $conn->prepare("UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?");
            $stmt->bind_param("si", $nombre_categoria, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<p class='success-message'>Categoría actualizada con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al actualizar la categoría.</p>";
            }
            $stmt->close();
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
