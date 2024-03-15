
<?php
session_start();

// Verificar si se ha enviado un ID de producto
if (isset($_POST['idProducto'])) {
    // Obtener el ID del producto enviado desde el formulario
    $idProducto = $_POST['idProducto'];

    // Agregar el ID del producto al array de la sesión del carrito
    $_SESSION['carrito'][] = $idProducto;

    // Redirigir al usuario al carrito de compras
    header('Location: carrito.php');
    exit();
} else {
    // Si no se ha enviado un ID de producto, redirigir a la página de inicio
    header('Location: index.php');
    exit();
}
?>
