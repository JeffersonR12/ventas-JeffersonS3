<?php
include 'conexion.php';

if ($_SERVER["POST_METHOD"] == "POST" || isset($_POST['cliente'])) {
    $cliente  = $_POST['cliente'];
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $precio   = $_POST['precio'];
    $total    = $_POST['total'];

    // Limpiar el total (quitar el "S/ " si viene del script.js)
    $total_limpio = str_replace("S/ ", "", $total);

    $sql = "INSERT INTO ventas (cliente, producto, cantidad, precio, total) 
            VALUES ('$cliente', '$producto', '$cantidad', '$precio', '$total_limpio')";

    if (mysqli_query($conexion, $sql)) {
        echo "Éxito"; // Esto lo leerá el script.js
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
mysqli_close($conexion);
?>