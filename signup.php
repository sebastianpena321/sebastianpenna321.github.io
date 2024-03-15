<?php
require 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documentoID = $_POST["documentoID"];
    $nombre = $_POST["nombre"];
    $numero = $_POST["numero"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $dirreccion = $_POST["dirreccion"];
    $defaultRoleId = 3;

    if (!empty($documentoID) && !empty($nombre) && !empty($numero) && !empty($email) && !empty($password) && !empty($dirreccion)) {
        // Verificar si el correo electrónico ya está registrado
        $verificarEmail = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = $conn->query($verificarEmail);

        if ($result->num_rows > 0) {
            $mensaje = "El correo electrónico ya está registrado. Por favor, elige otro.";
        } else {
            // Encriptar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);



            // Insertar datos en la tabla usuarios
            $Guardardatos = "INSERT INTO usuarios (documentoID, nombre, numero, email, dirreccion, password, rol_idRol) VALUES ('$documentoID','$nombre', '$numero', '$email', '$dirreccion', '$hashedPassword', '$defaultRoleId')";

            if ($conn->query($Guardardatos) === TRUE) {
                $mensaje = "Registro exitoso";
            } else {
                $mensaje = "Error al registrar: " . $conn->$error;
            }
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/signup.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon .ico" type="image/x-icon">
    <title>INICIO DE SECTION</title>
</head>

<body>
    <header>
        <a href="index.php" class="logo"> <img src="Imagenes/Logo .png" alt="Icono de la empresa">Moto Club</a>
        <nav>
            <ul>
                <li id="inicio"><a href="index.php">Inicio</a></li>
                <li><a href="reserva.php">Reservas</a></li>
                <li><a href="repuestos.php">Repuestos</a></li>
                <li><a href="pqrs.php">PQRS</a></li>
                <li><a href="contactos.php">Contactos</a></li>
            </ul>
            <a href="login.php"><button class>Iniciar sesión</button></a>
        </nav>
    </header>
    <div class="iniciar-secion">
        <div class="iniciar-secion_inicio">
            <h1>Registrate</h1>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($mensaje)): ?>

                <p>
                    <?php echo $mensaje; ?>
                </p>

            <?php endif; ?>
            <form action="signup.php" method="POST">
                <div class="input">
                    <span><i class='bx bx-envelope'></i></span>
                    <input type="number" name="documentoID" placeholder="Documento de identificación">
                </div>
                <div class="input">
                    <span><i class='bx bx-envelope'></i></span>
                    <input type="text" name="nombre" placeholder="Nombre completo">
                </div>
                <div class="input">
                    <span><i class='bx bx-phone'></i></span>
                    <input type="number" name="numero" placeholder="Número" maxlength="10">
                </div>
                <div class="input">
                    <span><i class='bx bx-envelope'></i></span>
                    <input type="email" name="email" placeholder="Correo electrónico">
                </div>
                <div class="input">
                    <span><i class='bx bx-lock-alt'></i></span>
                    <input type="text" name="dirreccion" placeholder="Dirreción">
                </div>
                <div class="input">
                    <span><i class='bx bx-lock-alt'></i></span>
                    <input type="password" name="password" placeholder="Contraseña">
                </div>

                <button>REGISTRATE</button>
                <div class="registrar">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
                </div>

            </form>
        </div>
    </div>
</body>

</html>