<?php
include '../includes/db_connect.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT id_producto, nombre_producto FROM productos WHERE nombre_producto LIKE ?");
    $search = "%" . $query . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<li data-id="' . $row['id_producto'] . '">' . htmlspecialchars($row['nombre_producto']) . '</li>';
    }
    $stmt->close();
}
?>
