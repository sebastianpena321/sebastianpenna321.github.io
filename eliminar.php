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

// Obtener el ID del usuario a eliminar
$idUsuario = $_GET["idUsuarios"];

// Iniciar una transacción
$conn->begin_transaction();

// Eliminar las filas relacionadas en la tabla "pqrs"
$eliminarPqrsQuery = "DELETE FROM pqrs WHERE cliente_idCliente = '$idUsuario'";
if ($conn->query($eliminarPqrsQuery) === TRUE) {
  // Luego de eliminar las filas relacionadas, puedes eliminar el usuario
  $eliminarUsuarioQuery = "DELETE FROM usuarios WHERE idUsuarios = '$idUsuario'";
  if ($conn->query($eliminarUsuarioQuery) === TRUE) {
    // Usuario eliminado correctamente
    // Confirmar la transacción
    $conn->commit();
    header("Location: clientes.php");
    exit;
  } else {
    // Error al eliminar el usuario
    echo "Error al eliminar el usuario: " . $conn->error;
    // Revertir la transacción en caso de error
    $conn->rollback();
  }
} else {
  // Error al eliminar las filas relacionadas en la tabla "pqrs"
  echo "Error al eliminar las filas relacionadas en la tabla 'pqrs': " . $conn->error;
  // Revertir la transacción en caso de error
  $conn->rollback();
}
?>
