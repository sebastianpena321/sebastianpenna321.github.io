<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"]) || $_SESSION["nombre"] == '') {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $servicio = $_POST["servicio"];
    $descripcion = $_POST["descripcion"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];

    // Validar y guardar la reserva en la base de datos
    require "conexion.php";
    $cliente_idCliente = $_SESSION["idUsuarios"]; // Obtener el ID del cliente desde la sesión

    $query = "INSERT INTO reserva (servicio, descripcion, fecha, hora, cliente_idCliente)
              VALUES ('$servicio', '$descripcion', '$fecha', '$hora', '$cliente_idCliente')";

    if (mysqli_query($conn, $query)) {
        // La reserva se ha guardado con éxito
        $mensaje = "Reserva realizada correctamente.";

    } else {
        // Hubo un error al guardar la reserva
        $mensaje = "Error al realizar la reserva: " . mysqli_error($conn);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/reservas.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon .ico" type="image/x-icon">
    <title>RESERVAS</title>
</head>

<body>
<header>
        <a href="index.php" class="logo"> <img src="Imagenes/Logo .png" alt="Icono de la empresa">Moto Club</a>
        <nav>
            <ul>
                <li id="inicio"><a href="index.php">Inicio</a></li>
                <li><a href="repuestos.php">Repuestos</a></li>
                <li><a href="pqrs.php">PQRS</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="Carrito.php"> Ver Carrito</a></li>
            </ul>
            <?php
            if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
                // El usuario ha iniciado sesión, muestra su nombre en su lugar
                echo '<div class="foto">';
                echo '<span><i class="bx bx-user"></i></span>';
                echo '<span class="nombre-usuario">' . $_SESSION["nombre"] . '</span>';
                echo '</div>';
                echo '<a href="logout.php"><button>Cerrar sesión</button></a>';
                echo '<a href="dashboard.php"><button>Perfil</button></a>'; // Agrega este enlace para ir a dashboard.php
            } else {
                // El usuario no ha iniciado sesión, muestra los botones "Iniciar sesión" y "Registrarse"
                echo '<a href="login.php"><button>Iniciar sesión</button></a>';
                echo '<a href="signup.php"><button>Registrarse</button></a>';
            }
            ?>
        </nav>
    </header>
    <div class="reservas">
        <h2>Hacer una Reserva</h2>
        <?php if (isset($mensaje)): ?>
            <p>
                <?php echo $mensaje; ?>
            </p>
        <?php endif; ?>
        <form action="reserva.php" method="POST">
            <div class="form-group">
                <label for="servicio">Servicio:</label>
                <select id="servicio" name="servicio" required>
                    <option value="mantenimiento general">Mantenimiento General</option>
                    <option value="sincronizacion">Sincronización</option>
                    <option value="reparacion de motor">Reparación de Motor</option>
                    <option value="servicio de escaner">Servicio de Escáner</option>
                    <option value="cambio de aceite">Cambio de Aceite</option>
                    <option value="acesorios">Accesorios</option>
                </select>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora" required>
            </div>
            <button type="submit">Hacer Reserva</button>
        </form>
    </div>
    </div>
</body>

</html>