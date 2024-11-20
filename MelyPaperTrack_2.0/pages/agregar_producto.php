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
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/productos.css">
    <title>Agregar Producto</title>
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
        <h1>Agregar Producto</h1>

        <form action="agregar_producto.php" method="post" enctype="multipart/form-data">
            <label for="nombre_producto">Nombre del producto:</label>
            <input type="text" id="nombre_producto" name="nombre_producto" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"></textarea>
            
            <label for="cantidad_stock">Cantidad en stock:</label>
            <input type="number" id="cantidad_stock" name="cantidad_stock" required>
            
            <label for="precio_compra">Precio de compra:</label>
            <input type="number" step="0.01" id="precio_compra" name="precio_compra">
            
            <label for="precio_venta">Precio de venta:</label>
            <input type="number" step="0.01" id="precio_venta" name="precio_venta">
            
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="id_categoria">
                <!-- Opciones de categorías -->
                <?php
                    $result = $conn->query("SELECT * FROM categorias");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row['id_categoria'].'">'.$row['nombre_categoria'].'</option>';
                    }
                ?>
            </select>
            
            <label for="proveedor">Proveedor:</label>
            <select id="proveedor" name="id_proveedor">
                <!-- Opciones de proveedores -->
                <?php
                    $result = $conn->query("SELECT * FROM proveedores");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row['id_proveedor'].'">'.$row['nombre_proveedor'].'</option>';
                    }
                ?>
            </select>
            
            <label for="foto">Foto del producto:</label>
            <input type="file" id="foto" name="foto">
            
             <button type="submit" name="add">Agregar producto</button>
        <a href="productos.php" class="btn-volver">Volver</a>
        </form>

        <?php
        if (isset($_POST['add'])) {
            $nombre = $_POST['nombre_producto'];
            $descripcion = $_POST['descripcion'];
            $stock = $_POST['cantidad_stock'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $categoria = $_POST['id_categoria'];
            $proveedor = $_POST['id_proveedor'];

            // Manejo de la imagen
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                // Mueve la imagen al directorio de uploads y guarda la ruta en la base de datos
                $fotoPath = 'uploads/' . basename($_FILES['foto']['name']);
                move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);
                $foto = $fotoPath;
            } else {
                $foto = ''; // Si no se carga una foto, deja el campo vacío
            }

            // Insertar producto en la base de datos
            $stmt = $conn->prepare("INSERT INTO productos (nombre_producto, descripcion, cantidad_stock, precio_compra, precio_venta, id_categoria, id_proveedor, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssddsiss", $nombre, $descripcion, $stock, $precio_compra, $precio_venta, $categoria, $proveedor, $foto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<p class='success-message'>Producto agregado con éxito.</p>";
            } else {
                echo "<p class='error-message'>Error al agregar el producto.</p>";
            }
            $stmt->close();
        }
        ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
