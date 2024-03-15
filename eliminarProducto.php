<?php
session_start();

if (isset($_POST['idProducto'])) {
    // Incluir archivo de conexión a la base de datos
    require "conexion.php";

    // Obtener el id del producto a eliminar
    $idProducto = $_POST['idProducto'];

    // Consulta SQL para eliminar el producto
    $query = "DELETE FROM producto WHERE idProducto = $idProducto";

    if ($conn->query($query) === TRUE) {
        // Producto eliminado exitosamente
        $_SESSION["mensaje_eliminar"] = "El producto se ha eliminado correctamente.";
    } else {
        // Error al eliminar el producto
        $_SESSION["mensaje_eliminar"] = "Error al eliminar el producto: " . $conn->error;
    }

    // Redirigir de regreso a la página de repuestos.php
    header("Location: repuestos.php");
    exit();
} else {
    // Si no se proporcionó un idProducto, redirigir de vuelta a la página de repuestos.php
    header("Location: repuestos.php");
    exit();
}
?>
