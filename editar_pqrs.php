<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"]) || empty($_SESSION["nombre"])) {
  // Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión
  header("Location: login.php");
  exit;
}

// Verificar si se proporcionó un ID de usuario válido
if (!isset($_GET["idPqrs"]) || empty($_GET["idPqrs"])) {
  // Redirigir a una página de error o a la lista de usuarios
  header("Location: login.php");
  exit;
}

// Obtener el ID del usuario a editar
$idPqrs = $_GET["idPqrs"];

// Incluir archivo de conexión
require_once "conexion.php";

// Obtener los datos del usuario de la base de datos
$query = "SELECT * FROM pqrs WHERE idPqrs = '$idPqrs'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // El usuario fue encontrado en la base de datos
  $row = $result->fetch_assoc();

  // Extraer los datos del usuario
  $tipo = $row["tipo"];
  $descripcion = $row["descripcion"];
} else {

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/editar.css">
    <title>Document</title>
</head>

<body>
    <aside>
        <a href="index.php" class="log"><img src="Logo .png" alt="logo">Moto Club</a>
        <ul>
            <li><a href="perfil.php"><span><i class='bx bx-face'></i></span>Perfil</a></li>
            <li><a href="inventario.php"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
            <li><a href="#"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
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

                if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] != '') {
                    echo '<div class="foto">';
                    echo '<span class="nombre-usuario">' . $_SESSION["nombre"] . '</span>';
                    echo '</div>';
                    echo '<a href="logout.php"><button>Cerrar sesión</button></a>';
                }
                ?>
            </div>
        </header>
        <div >
            <form action="actualizacionPqrs.php" method="POST">
                <input type="hidden" name="idPqrs" value="<?php echo $idPqrs; ?>">

                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="Peticion">Petición</option>
                    <option value="Queja">Queja</option>
                    <option value="Reclamo">Reclamo</option>
                </select>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>


                <button type="submit">Guardar cambios</button>
            </form>


        </div>
    </div>
    </div>
</body>

</html>