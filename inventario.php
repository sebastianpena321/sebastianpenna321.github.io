<?php
session_start();
$mensaje = "";

if (!isset($_SESSION["nombre"]) || empty($_SESSION["nombre"])) {
    // Redirigir al formulario de inicio de sesión si el usuario no ha iniciado sesión
    header("Location: login.php");
    exit;
}

require_once "conexion.php"; // Asegúrate de incluir tu archivo de conexión

// Obtener el rol del usuario actual
$rol_id = $_SESSION["rol_idRol"];

// Verificar si se ha enviado el formulario para agregar un producto
if (isset($_POST["costo"]) && isset($_POST["descripcion"]) && isset($_POST["nombre"]) && isset($_POST["cantidad"]) && isset($_POST["proveedor_idProveedor"])) {
    // Los valores del formulario están definidos, procedemos a agregar el producto al inventario
    $costo = mysqli_real_escape_string($conn, $_POST["costo"]);
    $descripcion = mysqli_real_escape_string($conn, $_POST["descripcion"]);
    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
    $cantidad = mysqli_real_escape_string($conn, $_POST["cantidad"]);
    $proveedor_idProveedor = mysqli_real_escape_string($conn, $_POST["proveedor_idProveedor"]);
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    $ruta_destino = 'Estilos/Inventario/' . $nombre; // Añade un '/' después de 'Inventario' para especificar un directorio

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {

        $query = "INSERT INTO producto (costo, descripcion, rutaImagen, nombre, cantidad, proveedor_idProveedor) VALUES ('$costo', '$descripcion','$ruta_destino', '$nombre', '$cantidad', '$proveedor_idProveedor')";

        $result = $conn->query($query);

        if ($result) {
            // El producto se agregó correctamente
            $mensaje = "El producto se agregó correctamente al inventario.";
        } else {
            // Ocurrió un error al agregar el producto
            $mensaje = "Ocurrió un error al agregar el producto al inventario.";
        }
    } else {
        $mensaje = "Error al mover el archivo al destino.";
    }
}

$usuario_id = $_SESSION["idUsuarios"]; 
$queryCarritosOtrosUsuarios = "SELECT * FROM usuarios WHERE 1 = 0"; // Consulta que no devolverá resultados


if ($rol_id == 1) {
    // Rol: Administrador (1) - Consulta para ver productos agregados por otros usuarios
    $queryProductosAgregados = "SELECT c.idCarrito, c.cantidad, c.fecha, c.cliente_idCliente, p.idProducto, p.costo, p.descripcion, p.rutaImagen, p.nombre, p.cantidad AS cantidad_producto, p.proveedor_idProveedor, u.idUsuarios, u.nombre AS nombre_usuario
                                FROM carrito c
                                INNER JOIN detalle_compra d ON c.idCarrito = d.carritoID
                                INNER JOIN producto p ON d.productoID = p.idProducto
                                INNER JOIN usuarios u ON c.cliente_idCliente = u.idUsuarios";
    
    // Rol: Administrador (1) - Consulta para ver carritos de otros usuarios
    $queryCarritosOtrosUsuarios = "SELECT c.idCarrito, c.cantidad, c.fecha, c.cliente_idCliente, u.idUsuarios, u.nombre AS nombre_usuario
                                  FROM carrito c
                                  INNER JOIN usuarios u ON c.cliente_idCliente = u.idUsuarios
                                  WHERE c.cliente_idCliente != $usuario_id"; // Excluir el carrito del administrador actual
} elseif ($rol_id == 2) {
    // Rol: Empleado (2) - Mostrar productos en carritos de empleados y usuarios
    $queryProductosAgregados = "SELECT c.idCarrito, c.cantidad, c.fecha, c.cliente_idCliente, p.idProducto, p.costo, p.descripcion, p.rutaImagen, p.nombre, p.cantidad AS cantidad_producto, p.proveedor_idProveedor, u.idUsuarios, u.nombre AS nombre_usuario
                                FROM carrito c
                                INNER JOIN detalle_compra d ON c.idCarrito = d.carritoID
                                INNER JOIN producto p ON d.productoID = p.idProducto
                                INNER JOIN usuarios u ON c.cliente_idCliente = u.idUsuarios
                                WHERE c.cliente_idCliente = $usuario_id"; // Mostrar solo carritos del empleado o usuario actual
    
    // Empleado (2) - Consulta para ver carritos de otros usuarios
    $queryCarritosOtrosUsuarios = "SELECT c.idCarrito, c.cantidad, c.fecha, c.cliente_idCliente, u.idUsuarios, u.nombre AS nombre_usuario
                                  FROM carrito c
                                  INNER JOIN usuarios u ON c.cliente_idCliente = u.idUsuarios
                                  WHERE c.cliente_idCliente != $usuario_id"; // Excluir el carrito del empleado actual
} elseif ($rol_id == 3) {
    // Rol: Usuario (3) - Mostrar productos en el carrito del usuario actual
    $queryProductosAgregados = "SELECT c.idCarrito, c.cantidad, c.fecha, c.cliente_idCliente, p.idProducto, p.costo, p.descripcion, p.rutaImagen, p.nombre, p.cantidad AS cantidad_producto, p.proveedor_idProveedor, u.idUsuarios, u.nombre AS nombre_usuario
                                FROM carrito c
                                INNER JOIN detalle_compra d ON c.idCarrito = d.carritoID
                                INNER JOIN producto p ON d.productoID = p.idProducto
                                INNER JOIN usuarios u ON c.cliente_idCliente = u.idUsuarios
                                WHERE c.cliente_idCliente = $usuario_id"; // Mostrar solo el carrito del usuario actual
    
    // Rol: Usuario (3) - No necesita ver carritos de otros usuarios, así que no definimos la consulta $queryCarritosOtrosUsuarios
}

// Ejecutar las consultas
$resultProductosAgregados = $conn->query($queryProductosAgregados);
$resultCarritosOtrosUsuarios = $conn->query($queryCarritosOtrosUsuarios);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Estilos/inventario.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="Estilos/imagenes/favicon .ico" type="image/x-icon">

    <title>PERFIL</title>
</head>

<body>
    <aside>
    <a href="dashboard.php" class="log"><img src="Imagenes/Logo .png" alt="logo">Moto Club</a>
        <ul>
            <li><a href="perfil.php" style="text-decoration:none;"><span><i class='bx bx-face'></i></span>Perfil</a></li>
            <li><a href="inventario.php" style="text-decoration:none;"><span><i class='bx bxs-cabinet'></i></span>Inventario</a></li>
            <li><a href="#" style="text-decoration:none;"><span><i class='bx bx-check-double'></i></span>Reservas</a></li>
            <li><a href="#" style="text-decoration:none;" ><span><i class='bx bx-question-mark'></i></span>PQRS</a></li>
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
        <div class="perfil">
            <div class="reservas-inicio">
                <h1>INVENTARIO</h1>

                <?php if ($rol_id == 1): ?>
                   
                    <form action="inventario.php" method="POST" enctype="multipart/form-data">
                        <label for="costo">Costo:</label>
                        <input type="number" name="costo" required>

                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion"></textarea>

                        <input type="file" name="imagen" accept="image/*">

                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" required>

                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" required>

                        <div class="form-group">
                            <label for="proveedor_idProveedor">Proveedor:</label>
                            <select id="tipo" name="proveedor_idProveedor" required>
                                <option value="YAMAHA">YAMAHA</option>
                                <option value="KTM">KTM</option>
                                <option value="HONDA">HONDA</option>
                                <option value="KAWASAKI">KAWASAKI</option>
                                <option value="DUCATI">DUCATI</option>
                                <option value="BAJAJ">BAJAJ</option>
                                <option value="SUZUKI">SUZUKI</option>
                                <option value="KYMCO">KYMCO</option>
                                <option value="HERO">HERO</option>
                                <option value="TVS">TVS</option>
                            </select>
                        </div>

                        <input type="submit" value="Guardar">
                    </form>

                <?php endif; ?>

                <h2>Productos en el inventario:</h2>

                <table class="table">
                    <tr>
                        <th>Costo</th>
                        <th>Descripción</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Proveedor</th>
                        <th>Imagen</th>
                        <?php if ($rol_id == 1): ?>
                         
                            <th>Usuario</th>
                        <?php endif; ?>
                        <?php if ($rol_id == 2 || $rol_id == 1): ?>
                            
                            <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                    <?php
                   
                    if ($resultProductosAgregados->num_rows > 0) {
                        while ($row = $resultProductosAgregados->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["costo"] . "</td>";
                            echo "<td>" . $row["descripcion"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["cantidad"] . "</td>";
                            echo "<td>" . $row["proveedor_idProveedor"] . "</td>";
                            echo '<td><img src="' . $row["rutaImagen"] . '" alt="Imagen del Producto" style="max-width: 150px; max-height: 150px;"></td>';
                            if ($rol_id == 1) {
                             
                                echo "<td>" . $row["nombre"] . "</td>";
                            }
                            if ($rol_id == 2 || $rol_id == 1) {
                               
                                echo '<td>';
                                echo '<a href="editar_producto.php?id=' . $row["idProducto"] . '">Editar</a>';
                                echo ' | ';
                                echo '<a href="eliminar_producto.php?id=' . $row["idProducto"] . '">Eliminar</a>';
                                echo '</td>';
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay productos en el inventario.</td></tr>";
                    }
                    if ($resultCarritosOtrosUsuarios->num_rows > 0) {
                      
                       
                        echo '<tr>';
                        echo '<th>ID del Producto</th>';
                        echo '<th>Nombre</th>';
                        echo '<th>Costo</th>';
                        echo '</tr>';
                
                        while ($row = $resultCarritosOtrosUsuarios->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row["idCarrito"] . '</td>';
                            echo '<td>' . $row["cantidad"] . '</td>';
                            echo '<td>' . $row["fecha"] . '</td>';
                            echo '</tr>';
                        }
                
                        echo '</table>';
                    } else {
                        // Si no hay productos en el carrito, mostrar un mensaje
                        echo '<p>No hay productos en el carrito.</p>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
