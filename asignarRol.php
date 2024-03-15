<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"]) || empty($_SESSION["nombre"])) {
  // Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión
  header("Location: login.php");
  exit;
}

// Incluir archivo de conexión
require_once "conexion.php";

// Verificar si se recibió el ID del usuario por GET o POST
if (isset($_REQUEST["idUsuarios"])) {
  $idUsuario = $_REQUEST["idUsuarios"];

  // Obtener los datos del usuario por ID
  $query = "SELECT * FROM usuarios WHERE idUsuarios = $idUsuario";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // El usuario fue encontrado en la base de datos
    $row = $result->fetch_assoc();

    // Extraer los datos del usuario
    $nombre = $row["nombre"];
    $email = $row["email"];
    $telefono = $row["numero"];

    // Verificar si se recibió el rol por POST
    if (isset($_POST["rol"])) {
      $nuevoRol = $_POST["rol"];

      // Verificar si el nuevo rol es un valor válido (1 para administrador, 2 para empleado, 3 para cliente)
      if ($nuevoRol >= 1 && $nuevoRol <= 3) {
        // Actualizar el rol del usuario
        $updateQuery = "UPDATE usuarios SET rol_idRol = '$nuevoRol' WHERE idUsuarios = $idUsuario";
        if ($conn->query($updateQuery) === TRUE) {
          // Redirigir de vuelta a la página de clientes después de asignar el rol
          header("Location: clientes.php");
          exit;
        } else {
          // Manejar el error de actualización si es necesario
          echo "Error al actualizar el rol: " . $conn->error;
        }
      } else {
        // Manejar el caso de un rol no válido
        echo "El rol especificado no es válido.";
      }
    }
  } else {
    // El usuario no fue encontrado en la base de datos
    // Manejar el caso apropiado, como mostrar un mensaje de error o redirigir a una página de error
  }
} else {
  // Si no se proporcionó el ID del usuario, redirigir a la página de clientes
  header("Location: clientes.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="Estilos/asignarRol.css">
  <title>Document</title>
</head>

<body>
  <aside>
    <a href="dashboard.php" class="log"><img src="Logo .png" alt="logo">Moto Club</a>
    <ul>
      <li><a href="perfil.php"><span><i class='bx bx-face'></i></span>Perfil</a></li>
      <li><a href="inventario.php"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
      <li><a href="#"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
      <li><a href="#"><span><i class='bx bx-question-mark'></i></span>PQRS</a></li>
      <?php

      ?>
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
        <?php if (isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"])): ?>
          <div class="foto">
            <span class="nombre-usuario">
              <?php echo $_SESSION["nombre"]; ?>
            </span>
          </div>
          <a href="logout.php"><button>Cerrar sesión</button></a>
        <?php endif; ?>
      </div>
    </header>


  </div>
  <div class="asignar-rol">
    <h2>Asignar Rol</h2>
    <form action="asignarRol.php" method="POST">
      <input type="hidden" name="idUsuarios" value="<?php echo $row['idUsuarios']; ?>">

      <label for="rol">Seleccionar Rol:</label>
      <select name="rol" id="rol">
        <option value="1">Administrador</option>
        <option value="2">Empleado</option>
        <option value="3">Cliente</option>
      </select>

      <button type="submit">Asignar Rol</button>
    </form>
  </div>
  </div>
</body>

</html>