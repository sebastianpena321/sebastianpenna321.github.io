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
                echo '<a href="login.php"><button>Iniciar sesión</button></a>';
                echo '<a href="signup.php"><button>Registrarse</button></a>';
            }
            ?>
            <!-- Mostrar el carrito -->
            
        </nav>
    </header>
    <section>    
        <h1>Productos Recientes</h1>
        <div class="productos-recientes">
            <?php
            // Consulta SQL para obtener los productos de la base de datos
            $query = "SELECT * FROM producto ORDER BY idProducto";
            $result = $conn->query($query);

            if ($result !== false) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="producto">';
                    echo '<img src="' . $row["rutaImagen"] . '" alt="' . $row["nombre"] . '">';
                    echo '<h2>' . $row["nombre"] . '</h2>';
                    echo '<p>Descripción: ' . $row["descripcion"] . '</p>';
                    echo '<p>Costo: $' . $row["costo"] . '</p>';
                    echo '<form method="post" action="agregar_al_carrito.php">';
                    echo '<input type="hidden" name="idProducto" value="' . $row["idProducto"] . '">';
                    echo '<button type="submit" style="background-color: red; color: white;">Agregar al carrito</button>';


                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>Error en la consulta SQL.</p>';
            }

            if ($result->num_rows == 0) {
                // No hay productos recientes
                echo '<p>No hay productos recientes.</p>';
            }
            ?>
        </div>
    </section>
    <footer>
        <!-- Pie de página aquí -->
    </footer>
</body>

</html>
