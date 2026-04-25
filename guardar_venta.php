<?php
require 'conexion.php'; // Archivo que configura Eloquent
use App\Models\Venta;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modeloVenta = new Venta();
    
    // Limpiamos el total por si viene con el símbolo "S/ "
    $totalLimpio = floatval(str_replace("S/ ", "", $_POST['total']));

    $datos = [
        'cliente'  => trim($_POST['cliente']),
        'producto' => trim($_POST['producto']),
        'cantidad' => floatval($_POST['cantidad']),
        'precio'   => floatval($_POST['precio']),
        'total'    => $totalLimpio
    ];

    // Aplicamos la validación que probamos en el Test
    if (!$modeloVenta->esValida($datos)) {
        echo "Error: Datos de venta inválidos.";
        exit;
    }

    // Guardado eficiente con ORM
    try {
        Venta::create($datos);
        echo "Éxito";
    } catch (\Exception $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}