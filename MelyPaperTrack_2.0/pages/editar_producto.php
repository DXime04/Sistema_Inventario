<?php 
session_start(); 
include '../includes/db_connect.php'; 

$id = $_GET['id'];

// Obtener los datos del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Producto no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/productos.css">
    <title>Editar Producto</title>
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
        <h1>Editar Producto</h1>

        <form action="editar_producto.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre del producto:</label>
            <input type="text" id="nombre" name="nombre_producto" value="<?php echo htmlspecialchars($product['nombre_producto']); ?>" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($product['descripcion']); ?>">

            <label for="stock">Cantidad en stock:</label>
            <input type="number" id="stock" name="cantidad_stock" value="<?php echo htmlspecialchars($product['cantidad_stock']); ?>" required>

            <label for="precio_compra">Precio de compra:</label>
            <input type="text" id="precio_compra" name="precio_compra" value="<?php echo htmlspecialchars($product['precio_compra']); ?>" required>

            <label for="precio_venta">Precio de venta:</label>
            <input type="text" id="precio_venta" name="precio_venta" value="<?php echo htmlspecialchars($product['precio_venta']); ?>" required>

            <label for="categoria">Categoría:</label>
            <select name="id_categoria">
                <?php
                $result = $conn->query("SELECT * FROM categorias");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id_categoria'].'"'.($row['id_categoria'] == $product['id_categoria'] ? ' selected' : '').'>'.$row['nombre_categoria'].'</option>';
                }
                ?>
            </select>

            <label for="proveedor">Proveedor:</label>
            <select name="id_proveedor">
                <?php
                $result = $conn->query("SELECT * FROM proveedores");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row['id_proveedor'].'"'.($row['id_proveedor'] == $product['id_proveedor'] ? ' selected' : '').'>'.$row['nombre_proveedor'].'</option>';
                }
                ?>
            </select>

            <label for="foto">Foto:</label><br>
            <input type="file" id="foto" name="foto" accept="image/*" required><br>

            <?php if (!empty($product['foto'])): ?>
                <p>Imagen actual:</p>
                <img src="<?php echo htmlspecialchars($product['foto']); ?>" alt="Foto actual" width="100">
            <?php endif; ?>

            <button type="submit" name="update">Actualizar producto</button>
            <a href="productos.php" class="btn-volver">Volver</a>
        </form>

        <?php
        if (isset($_POST['update'])) {
            $nombre = $_POST['nombre_producto'];
            $descripcion = $_POST['descripcion'];
            $stock = $_POST['cantidad_stock'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $categoria = $_POST['id_categoria'];
            $proveedor = $_POST['id_proveedor'];

            // Manejo de la imagen
            if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
                // Mueve la imagen al directorio de uploads y guarda la ruta en la base de datos
                $fotoPath = 'uploads/' . basename($_FILES['Foto']['name']);
                move_uploaded_file($_FILES['Foto']['tmp_name'], $fotoPath);
                $foto = $fotoPath;
            } else {
                // Si no se envió una nueva foto, conserva la foto actual
                $foto = $product['foto'];
            }

            // Actualizar producto en la base de datos
            $stmt = $conn->prepare("UPDATE productos SET nombre_producto = ?, descripcion = ?, cantidad_stock = ?, precio_compra = ?, precio_venta = ?, id_categoria = ?, id_proveedor = ?, foto = ? WHERE id_producto = ?");
            $stmt->bind_param("ssddsissi", $nombre, $descripcion, $stock, $precio_compra, $precio_venta, $categoria, $proveedor, $foto, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<p class='success-message'>Producto actualizado con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al actualizar el producto.</p>";
            }
            $stmt->close();
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
