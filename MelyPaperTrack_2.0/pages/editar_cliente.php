<?php 
session_start(); 
include '../includes/db_connect.php'; 

if (!isset($_GET['id'])) {
    header('Location: clientes.php');
    exit;
}

$id_cliente = $_GET['id'];

// Obtener información del cliente
$stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p class='error-message'>Cliente no encontrado.</p>";
    exit;
}

$cliente = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../CSS/boton_usuario.css">
    <link rel="stylesheet" href="../css/clientes.css">
    <title>Editar Cliente</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Editar Cliente</h1>
        <form id="editarClienteForm" method="POST">
            <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">
            <label for="nombre_cliente">Nombre:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" value="<?php echo htmlspecialchars($cliente['nombre_cliente']); ?>" required><br>

            <label for="contacto_cliente">Contacto:</label>
            <input type="text" id="contacto_cliente" name="contacto_cliente" value="<?php echo htmlspecialchars($cliente['contacto_cliente']); ?>" required><br>

            <label for="email_cliente">Email:</label>
            <input type="email" id="email_cliente" name="email_cliente" value="<?php echo htmlspecialchars($cliente['email_cliente']); ?>"><br>

            <label for="telefono_cliente">Teléfono:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente" value="<?php echo htmlspecialchars($cliente['telefono_cliente']); ?>"><br>

            <button type="submit" id="updateButton">Actualizar cliente</button>
            <a href="clientes.php" class="btn-volver">Volver</a>
        </form>

        <div id="responseMessage"></div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <script>
    $(document).ready(function(){
        $('#editarClienteForm').on('submit', function(event){
            event.preventDefault(); // Evita el envío tradicional del formulario

            $.ajax({
                url: 'procesar_editar_cliente.php',
                method: 'POST',
                data: $(this).serialize(), // Serializa los datos del formulario
                success: function(response) {
                    $('#responseMessage').html(response);
                },
                error: function() {
                    $('#responseMessage').html('<p class="error-message">Error al procesar la solicitud.</p>');
                }
            });
        });
    });
    </script>
</body>
</html>
