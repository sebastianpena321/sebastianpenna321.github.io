<?php
session_start();
require "conexion.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"]) || $_SESSION["nombre"] == '') {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Inicializamos la consulta SQL
$query = "";
$titulo = "";

switch ($_SESSION["rol_idRol"]) {
    case 1:
        // Consulta SQL para administradores (todas las reservas)
        $query = "SELECT r.*, u.nombre AS nombre_cliente 
                  FROM reserva r
                  INNER JOIN usuarios u ON r.cliente_idCliente = u.idUsuarios";
        $titulo = "Todas las Reservas";
        break;

    default:
        // Consulta SQL para usuarios normales y empleados (reservas del usuario actual)
        $cliente_idCliente = $_SESSION["idUsuarios"];
        $query = "SELECT r.*, u.nombre AS nombre_cliente 
                  FROM reserva r
                  INNER JOIN usuarios u ON r.cliente_idCliente = u.idUsuarios
                  WHERE r.cliente_idCliente = '$cliente_idCliente'";
        $titulo = "Tus Reservas";
        break;
}

$resultado = mysqli_query($conn, $query);

// Obtener el número total de Reservas
$total_reservas = mysqli_num_rows($resultado);

// Cerrar la conexión a la base de datos
mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/pqrsdb.css">
    <title>Reservas</title>
</head>

<body>
    <aside>
        <a href="dashboard.php" class="log">
            <img src="Logo .png" alt="logo">Moto Club
        </a>
        <ul>
            <li><a href="perfil.php"><span><i class='bx bx-face'></i></span>Perfil</a></li>
            <li><a href="inventario.php"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
            <li><a href="reservadb.php"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
            <li><a href="pqrsdb.php"><span><i class='bx bx-question-mark'></i></span>PQRS</a></li>
            
        </ul>
    </aside>
    <div class="contenido">
        <header>
            <div class="contenido-buscar">
                <span><i class='bx bx-search-alt-2'></i></span>
                <input type="search" placeholder="Buscar">
            </div>
            <div class="contenido-perfil">
                <span><i class='bx bx-bell'></i></span>
                <span><i class='bx bx-message-dots'></i></span>

                <?php
                echo '<div class="foto">';
                echo '<span class="nombre-usuario">' . $_SESSION["nombre"] . '</span>';
                echo '</div>';
                echo '<a href="logout.php"><button>Cerrar sesión</button></a>';
                ?>
            </div>
        </header>
        <div class="reservas-inicio">
        <h2>
            <?php echo $titulo; ?>
        </h2>
        <?php
        if ($total_reservas > 0) {
            // Mostrar las Reservas en una tabla
            echo '<table>';
            echo '<tr>
                <th>ID</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>';
            
            if ($_SESSION["rol_idRol"] === "administrador") {
                echo '<th>Acciones</th>';
            } else {
                echo '<th>Editar</th>';
                echo '<th>Eliminar</th>';
            }

            echo '</tr>';

            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo '<tr>';
                echo '<td>' . $fila["idReserva"] . '</td>';
                echo '<td>' . $fila["servicio"] . '</td>';
                echo '<td>' . $fila["descripcion"] . '</td>';
                echo '<td>' . $fila["fecha"] . '</td>';
                echo '<td>' . $fila["hora"] . '</td>';
                echo '<td>' . $fila["nombre_cliente"] . '</td>';
                
                if ($_SESSION["rol_idRol"] === "administrador") {
                    // Para administradores, mostrar acciones de editar y eliminar
                    echo '<td><a href="editar_reserva.php?idReserva=' . $fila["idReserva"] . '">Editar</a></td>';
                    echo '<td><a href="eliminar_reserva.php?idReserva=' . $fila["idReserva"] . '">Eliminar</a></td>';
                } else {
                    // Para usuarios y empleados, mostrar acciones de editar y eliminar
                    echo '<td><a href="editar_reserva.php?idReserva=' . $fila["idReserva"] . '">Editar</a></td>';
                    echo '<td><a href="eliminar_reserva.php?idReserva=' . $fila["idReserva"] . '">Eliminar</a></td>';
                }

                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No hay reservas registradas.</p>';
        }
        ?>
    </div>
</body>

</html>