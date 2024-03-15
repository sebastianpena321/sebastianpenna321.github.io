<?php
require 'conexion.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $loggedIn = true;
} else {
    $loggedIn = false;
}

require 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {
        $consulta = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = $conn->query($consulta);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            if ($password = $row["password"]) {
                $_SESSION["loggedin"] = true;

                $consulta = "SELECT idUsuarios, nombre, rol_idRol FROM usuarios WHERE email = '$email'";
                $resultado = $conn->query($consulta);


                if ($resultado->num_rows == 1) {
                    $row = $resultado->fetch_assoc();
                    $_SESSION["rol_idRol"] = $row["rol_idRol"]; // Establecemos el rol del usuario para usarlo despues
                    $_SESSION["idUsuarios"] = $row["idUsuarios"]; // Establecemos el id del usuario para usarlo despues
                    $_SESSION["nombre"] = $row["nombre"];


                    switch ($_SESSION["rol_idRol"]) {
                        case 1:

                            header("Location: clientes.php");
                            break;
                        case 2:
                            // El usuario es vendedor
                            header("Location: dashboard.php");
                            break;
                        case 3:
                            // El usuario es cliente
                            header("Location: dashboard.php");
                            break;
                        default:
                            // El usuario no tiene un rol válido
                            echo "El usuario no tiene un rol válido.";
                    }

                    header("Location: index.php");
                    exit();
                }
            } else {
                $mensaje = "La contraseña es incorrecta.";
            }
        } else {
            $mensaje = "El correo electrónico no está registrado.";
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
    <link rel="stylesheet" href="Estilos/login.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon .ico" type="image/x-icon">
    <title>Document</title>
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
                <li><a href="Carrito.php"> Ver Carrito</a></li>
            </ul>
            <a href="signup.php" ><button class>Registrate</button></a> 
        </nav>
    </header>

    <div class="iniciar-secion">
        <div class="iniciar-secion_inicio">
            <h1>inicia sesión</h1>
            <?php if (!empty($mensaje)): ?>



                <p>
                    <?php echo $mensaje; ?>

                </p>



            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="input">
                    <span><i class='bx bx-envelope'></i></span>
                    <input type="email" name="email" placeholder="Correo electronico">
                </div>
                <div class="input">
                    <span><i class='bx bx-lock-alt'></i></span>
                    <input type="password" name="password" placeholder="Contraseña">
                </div>
                <div class="recordar">
                    <label for="recuerdame"><input type="checkbox" id="recuerdame"> Recuerdame</label>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
                <button>Iniciar sesión</button>

                <div class="registrar">
                    <p>¿No tienes cuenta? <a href="signup.php">Registrate</a></p>
                </div>
            </form>
        
        </div>
    </div>
</body>

</html>