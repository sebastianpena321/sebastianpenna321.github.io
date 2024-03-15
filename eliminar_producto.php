<?php
session_start();

// Verificar si se ha enviado un ID de producto para eliminar
if (isset($_POST['idProducto'])) {
    // Obtener el ID del producto enviado desde el formulario
    $idProducto = $_POST['idProducto'];

    // Eliminar el producto del carrito
    eliminarProductoDelCarrito($idProducto);

    // Redirigir de nuevo al carrito
    header('Location: carrito.php');
    exit();
} else {
    // Si no se ha enviado un ID de producto, redirigir a la página de inicio
    header('Location: index.php');
    exit();
}

// Función para eliminar un producto del carrito por su ID
function eliminarProductoDelCarrito($idProducto) {
    // Verificar si el carrito está vacío
    if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
        return;
    }

    // Buscar el índice del producto en el carrito
    $index = array_search($idProducto, $_SESSION["carrito"]);

    // Si se encontró el producto en el carrito, eliminarlo
    if ($index !== false) {
        unset($_SESSION["carrito"][$index]);
    }
}
?>
