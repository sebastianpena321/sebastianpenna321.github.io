<?php
session_start();
$mensaje = "";

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
        // El usuario ha iniciado sesión, proceder a guardar la PQR en la base de datos
        require "conexion.php";

        // Obtener los valores del formulario
        $tipo = mysqli_real_escape_string($conn, $_POST["tipo"]);
        $descripcion = mysqli_real_escape_string($conn, $_POST["descripcion"]);
        $fecha = date('Y-m-d');
        $estado = 'Activa'; // Por defecto, se establece como 'Activa'

        // Obtener el ID del cliente desde la sesión
        if (isset($_SESSION["idUsuarios"])) {
            $cliente_idCliente = $_SESSION["idUsuarios"];
        } else {
            // Si no se encuentra el valor en la sesión, muestra un mensaje de error o redirige al usuario.
            echo "Error: No se ha encontrado el ID del cliente en la sesión.";
            // Puedes redirigir al usuario a una página de error o a otra página apropiada.
            exit; // Termina el script para evitar que se ejecute más código.
        }

        // Insertar la PQR en la tabla "pqrs" junto con el ID del cliente
        $query = "INSERT INTO pqrs (tipo, descripcion, fecha, estado, cliente_idCliente)
              VALUES ('$tipo', '$descripcion', '$fecha', '$estado', '$cliente_idCliente')";

        if (mysqli_query($conn, $query)) {
            // La inserción se realizó con éxito
            $mensaje = "PQR enviada correctamente";
        } else {
            // Hubo un error al insertar los datos
            $mensaje = "Error al enviar la PQR: " . mysqli_error($conn);
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    } else {
        // El usuario no ha iniciado sesión, mostrar un mensaje de inicio de sesión requerido
        echo '<p>Debes iniciar sesión para poder enviar una PQR.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/pqrs.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon .ico" type="image/x-icon">
    <title>PQRS</title>
</head>

<body>
    <header>
        <a href="index.php" class="logo">
            <img src="Imagenes/Logo .png" alt="Icono de la empresa">Moto Club
        </a>
        <nav>
            <ul>
                <li id="inicio"><a href="index.php">Inicio</a></li>
                <li><a href="reserva.php">Reservas</a></li>
                <li><a href="repuestos.php">Repuestos</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="agregar_al_carrito.php"> Ver Carrito</a></li>
            </ul>
            <?php
            if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
                // El usuario ha iniciado sesión, muestra su nombre en su lugar
                echo '<div class="foto">';
                echo '<span><i class="bx bx-user"></i></span>';
                echo '<span class="nombre-usuario">' . $_SESSION["nombre"] . '</span>';
                echo '</div>';
                echo '<a href="logout.php"><button>Cerrar sesión</button></a>';
                echo '<a href="dashboard.php"><button>Perfil</button></a>';
            } else {
                // El usuario no ha iniciado sesión, muestra los botones "Iniciar sesión" y "Registrarse"
                echo '<a href="login.php"><button>Iniciar sesión</button></a>';
                echo '<a href="signup.php"><button>Registrarse</button></a>';
            }
            ?>
        </nav>
    </header>
    <section class="pqrs">
        <h2>PQRS</h2>

        <!-- Formulario para enviar PQRS -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="Peticion">Petición</option>
                    <option value="Queja">Queja</option>
                    <option value="Reclamo">Reclamo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>

            <?php
            if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
                // El usuario ha iniciado sesión, muestra el botón de enviar
                echo '<button type="submit">Enviar</button>';
            } else {
                // El usuario no ha iniciado sesión, muestra un mensaje de inicio de sesión requerido
                echo '<p>Debes iniciar sesión para poder enviar una PQR.</p>';
            }
            ?>
            <?php if (!empty($mensaje)): ?>
                <p>
                    <?php echo $mensaje; ?>
                </p>
            <?php endif; ?>
        </form>
        </section>
        <!-- Mostrar las PQRS existentes -->
        <div class="pqrs">
            <?php
            // Obtener las PQRS de la base de datos
            require "conexion.php";

            $query = "SELECT * FROM pqrs";

            $resultado = mysqli_query($conn, $query);

            $total_pqrs = mysqli_num_rows($resultado);

            if ($total_pqrs > 0) {
                // Mostrar las PQRS en una tabla
                echo '<table class="table">';
                echo '<tr>
                 <th>ID</th>
                 <th>Tipo</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Estado</th>
                
                </tr>';

                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo '<tr>';
                    echo '<td>' . $fila["idPqrs"] . '</td>';
                    echo '<td>' . $fila["tipo"] . '</td>';
                    echo '<td>' . $fila["descripcion"] . '</td>';
                    echo '<td>' . $fila["fecha"] . '</td>';
                    echo '<td>' . $fila["estado"] . '</td>';
                    
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>No hay PQRS registradas.</p>';
            }
            ?>
        </div>

    <footer>
        <div class="contactos"></div>
        <div class="redes"></div>
    </footer>
</body>

</html>