
  <?php
  session_start();

  // Incluir archivo de conexión
  require_once "conexion.php";

  // Verificar si el usuario ha iniciado sesión
  if (!isset($_SESSION["nombre"]) || empty($_SESSION["nombre"])) {
    // Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión
    header("Location: login.php");
    exit;
  }

  
  // Obtener los datos del usuario de la base de datos
  $nombreUsuario = $_SESSION["nombre"];

  // Consultar los datos del usuario
  $query = "SELECT * FROM usuarios WHERE nombre = '$nombreUsuario'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // El usuario fue encontrado en la base de datos
    $row = $result->fetch_assoc();

    // Extraer los datos del usuario
    $nombre = $row["nombre"];
    $email = $row["email"];
    $telefono = $row["numero"];
  } else {
    // El usuario no fue encontrado en la base de datos
    // Manejar el caso apropiado, como mostrar un mensaje de error o redirigir a una página de error
  }

  // Consultar los datos de todos los usuarios registrados
  $registroQuery = "SELECT * FROM usuarios";
  $registroResult = $conn->query($registroQuery);
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Estilos/perfil.css">
    <title>Document</title>
  </head>

  <body>
    <aside>
      <a href="dashboard.php" class="log"><img src="Imagenes/Logo .png" alt="logo">Moto Club</a>
      <ul>
        <li><a href="perfil.php"><span><i class='bx bx-face'></i></span>Perfil</a></li>
        <li><a href="inventario.php"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
        <li><a href="reservadb.php"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
        <li><a href="pqrsdb.php"><span><i class='bx bx-question-mark'></i></span>PQRS</a></li>
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

      <div class="registro">
        <h2>Usuarios Registrados</h2>
        <table class="table">
          <thead>
            <tr>
              <th>Documento</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Dirreccion</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($registroRow = $registroResult->fetch_assoc()):

              ?>

              <tr>
                <td>
                  <?php echo $registroRow["documentoID"]; ?>
                </td>
                <td>
                  <?php echo $registroRow["nombre"]; ?>
                </td>
                <td>
                  <?php echo $registroRow["email"]; ?>
                </td>
                <td>
                  <?php echo $registroRow["numero"]; ?>
                </td>
                <td>
                  <?php echo $registroRow["dirreccion"]; ?>
                </td>
                <td>
                  <?php echo $registroRow["rol_idRol"]; ?>
                </td>
                <td>
                  <a href="editar.php?idUsuarios=<?php echo $registroRow["idUsuarios"]; ?>">Editar</a>
                  <a href="eliminar.php?idUsuarios=<?php echo $registroRow["idUsuarios"]; ?>">Eliminar</a>
                  <a href="asignarRol.php?idUsuarios=<?php echo $registroRow["idUsuarios"]; ?>">Asignar Rol</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </body>

  </html>
  