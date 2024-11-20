<?php
// Incluir la conexión a la base de datos
include '../includes/db_connect.php'; // Verifica que la ruta sea correcta

session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginInput = $_POST['loginInput'];
    $password = $_POST['password'];

    // Verificar si el loginInput es un usuario o correo electrónico
    $sql = "SELECT * FROM usuarios WHERE (nombre_usuario = ? OR email_usuario = ?) AND password_usuario = ?";
    $stmt = $conn->prepare($sql); // Aquí usa la variable $conn que está definida en db_connect.php
    $stmt->bind_param("sss", $loginInput, $loginInput, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $_SESSION['user'] = $loginInput;
        header("Location: welcome.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/login_styles.css">
    <title>Iniciar Sesión - MelyPaperTrack</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Iniciar Sesión</h2>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="loginInput">Usuario o Correo</label>
                    <input type="text" class="form-control" id="loginInput" name="loginInput" placeholder="Ingresa tu usuario o correo" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <?php if($error != '') { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
