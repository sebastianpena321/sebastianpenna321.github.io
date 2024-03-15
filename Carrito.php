<?php
session_start();
require "conexion.php";

// Inicializar la sesión del carrito si aún no está inicializada
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = array();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Estilos/repuestos.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon.ico" type="image/x-icon">
    <title>REPUESTOS</title>
</head>

<body>
    <header>
    <a href="index.php" class="logo"> <img src="Imagenes/Logo .png" alt="Icono de la empresa">Moto Club</a>
        </a>
        <nav>
            <ul>
                <li id="inicio"><a href="index.php">Inicio</a></li>
                <li><a href="reserva.php">Reservas</a></li>
                <li><a href="repuestos.php">repuestos</a></li>
                <li><a href="pqrs.php">PQRS</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="carrito.php"">Ver Carrito</a></li>
             
            </ul>
            <?php
            $mensaje = "";

            // Mostrar el nombre del usuario si ha iniciado sesión
            if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
                echo '<div class="foto">';
                echo '<span><i class="bx bx-user"></i></span>';
                echo '<span class="nombre-usuario">' . $_SESSION["nombre"] . '</span>';
                echo '</div>';
                echo '<a href="logout.php"><button>Cerrar sesión</button></a>';
                echo '<a href="dashboard.php"><button>Perfil</button></a>';
            } else {
                // Mostrar botones de inicio de sesión y registro si no ha iniciado sesión
                echo '<form method="post" action="agregar_al_carrito.php">';

                echo '<a href="signup.php"><button>Registrarse</button></a>';
            }
            ?>
            <!-- Mostrar el carrito -->
            
        </nav>
    </header>
<?php
require "conexion.php"; // Asegúrate de incluir el archivo de conexión a la base de datos

// Función para obtener los detalles de un producto por su ID
function obtenerDetallesProducto($idProducto, $conn) {
    // Verificar si $idProducto es un número entero
    $idProducto = intval($idProducto);

    $query = "SELECT nombre, descripcion, costo, rutaImagen FROM producto WHERE idProducto = $idProducto";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        // Imprimir consulta SQL y cualquier error de la base de datos
        echo "Error en la consulta SQL: " . $conn->error;
        echo "Consulta SQL: " . $query;
        return false;
    }
}

// Función para eliminar un producto del carrito por su ID
function eliminarProductoDelCarrito($idProducto) {
    // Verificar si el carrito está vacío
    if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
        return false;
    }

    // Buscar el índice del producto en el carrito
    $index = array_search($idProducto, $_SESSION["carrito"]);

    // Si se encontró el producto en el carrito, eliminarlo
    if ($index !== false) {
        unset($_SESSION["carrito"][$index]);
        return true;
    }

    return false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
</head>

<body>
    <h1>Carrito de Compras</h1>

    <?php
    // Verificar si el carrito está vacío
    if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
        echo "<p>El carrito está vacío.</p>";
    } else {
        echo "<ul>";
        // Recorrer el array del carrito y mostrar los productos
        foreach ($_SESSION["carrito"] as $idProducto) {
            // Verificar si $idProducto es un número entero
            $idProducto = intval($idProducto);

            // Obtener los detalles del producto
            $detallesProducto = obtenerDetallesProducto($idProducto, $conn);
            
            // Verificar si se pudieron obtener los detalles del producto
            if ($detallesProducto !== false) {
                // Mostrar los detalles del producto
                echo "<li>";
                echo '<img src="' . $detallesProducto["rutaImagen"] . '" alt="' . $detallesProducto["nombre"] . '" width="100">';
                echo "<strong>Nombre:</strong> " . $detallesProducto["nombre"] . "<br>";
                echo "<strong>Descripción:</strong> " . $detallesProducto["descripcion"] . "<br>";
                echo "<strong>Costo:</strong> $" . $detallesProducto["costo"];

                // Agregar el botón de eliminar producto
                echo '<form method="post" action="eliminar_producto.php">';
                echo '<input type="hidden" name="idProducto" value="' . $idProducto . '">';
                echo '<button type="submit" style="background-color: red;">Eliminar</button>';

                echo '</form>';

                echo "</li>";
            } else {
                echo "<li>Producto con ID $idProducto no encontrado.</li>";
            }
        }
        echo "</ul>";
    }
    ?>

    <a href="repuestos.php">Continuar comprando</a>
</body>

</html>
