<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model {
    protected $table = 'ventas';
    public $timestamps = false;
    protected $fillable = ['cliente', 'producto', 'cantidad', 'precio', 'total'];

    /**
     * Lógica de la Kata TDD: Validación de negocio
     */
public function esValida($datos) {
    // Usar validaciones más estrictas
    if (empty(trim($datos['cliente'] ?? '')) || empty(trim($datos['producto'] ?? ''))) {
        return false;
    }

    if (($datos['cantidad'] ?? 0) <= 0 || ($datos['precio'] ?? 0) <= 0) {
        return false;
    }

    return true;
}

    public function calcularTotal($cantidad, $precio) {
        return $cantidad * $precio;
    }
}
