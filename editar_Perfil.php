<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["nombre"]) || empty($_SESSION["nombre"])) {
  // Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión
  header("Location: login.php");
  exit;
}

// Verificar si se proporcionó un ID de usuario válido
if (!isset($_GET["idUsuarios"]) || empty($_GET["idUsuarios"])) {
  header("Location: dashboard.php");
  exit;
}

// Obtener el id de la reserva para editar
$idUsuarios = $_GET["idUsuarios"];

// Incluir archivo de conexión
require_once "conexion.php";

// Obtener los datos de la reserva
$query = "SELECT * FROM Usuarios WHERE idUsuarios = '$idUsuarios'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  // La reserva fue encontrada en la base de datos
  $row = $result->fetch_assoc();

  // Extraer los datos de la reserva
  $documentoID = $row["documentoID"];
  $nombre = $row["nombre"];
  $numero = $row["numero"];
  $email = $row["email"];
  $dirreccion = $row["dirreccion"];
  $password = $row["password"];
  
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
  <link rel="stylesheet" href="Estilos/editar_perfil.css">
  <title>Document</title>
</head>
<body>
  <aside>
    <a href="dashboard.php" class="log"><img src="Logo .png" alt="logo">Moto Club</a>
    <ul>
      <li><a href="perfil.php"><span><i class='bx bx-face'></i></span>Perfil</a></li>
      <li><a href="inventario.php"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
      <li><a href="reservadb.php"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
      <li><a href="pqrsdb.php"><span><i class='bx bx-question-mark'></i></span>PQRS</a></li>
      
    </ul>
  </aside>
  <div class="contenido">
    <header>
      <!-- Barra de navegación superior -->
    </header>
    <div class="perfil">
      <div class="Perfil-informacion">
        <h1>Perfil</h1>
        <form action="update.php" method="POST">
          <input type="hidden" name="idUsuarios" value="<?php echo $idUsuarios; ?>">

          <label for="documento">Nuevo documento :</label>
          <input type="number" name="documentoID" id="documento" value="<?php echo $documentoID; ?>">

          <label for="nombre">Nuevo nombre :</label>
          <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>">

          <label for="numero">Nuevo numero :</label>
          <input type="number" name="numero" id="numero" value="<?php echo $numero; ?>">
          
          <label for="email">Nuevo correo :</label>
          <input type="text" name="email" id="email" value="<?php echo $email; ?>">

          <label for="dirreccion">Nueva dirección :</label>
          <input type="text" name="dirreccion" id="dirreccion" value="<?php echo $dirreccion; ?>">

          <label for="password">Nueva contraseña :</label>
          <input type="text" name="password" id="password" value="<?php echo $password; ?>">

          <button type="submit">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
