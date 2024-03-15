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

// Obtener el Id de la pqrs
$idPqrs = $_GET["idPqrs"];


$conn->begin_transaction();


$eliminarPqrsQuery = "DELETE FROM pqrs WHERE idPqrs = '$idPqrs'";
if ($conn->query($eliminarPqrsQuery) === TRUE) {
    $conn->commit();
    header("Location: pqrsdb.php");
    exit;
 
} else {
  // Error al eliminar la pqrs
  echo "Error al eliminar la pqrs en la tabla 'pqrs': " . $conn->error;
  
  $conn->rollback();
}
?>