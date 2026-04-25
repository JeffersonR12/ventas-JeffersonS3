<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Models\Venta;

class VentaTest extends TestCase {
    
    public function test_venta_con_datos_correctos_debe_ser_valida() {
        $venta = new Venta();
        $datos = [
            'cliente' => 'Juan Perez',
            'producto' => 'Laptop',
            'cantidad' => 1,
            'precio' => 1500
        ];
        $this->assertTrue($venta->esValida($datos));
    }

    public function test_venta_con_cantidad_cero_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => 'Test', 'producto' => 'Test', 'cantidad' => 0, 'precio' => 10];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_venta_con_cliente_vacio_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => '', 'producto' => 'Test', 'cantidad' => 1, 'precio' => 10];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_venta_con_producto_vacio_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => 'Test', 'producto' => '', 'cantidad' => 1, 'precio' => 10];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_venta_con_precio_cero_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => 'Test', 'producto' => 'Test', 'cantidad' => 1, 'precio' => 0];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_venta_con_precio_negativo_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => 'Test', 'producto' => 'Test', 'cantidad' => 1, 'precio' => -10];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_venta_con_cantidad_negativa_debe_ser_invalida() {
        $venta = new Venta();
        $datos = ['cliente' => 'Test', 'producto' => 'Test', 'cantidad' => -1, 'precio' => 10];
        $this->assertFalse($venta->esValida($datos));
    }

    public function test_calcular_total_con_valores_positivos() {
        $venta = new Venta();
        $this->assertEquals(1500, $venta->calcularTotal(3, 500));
    }

    public function test_calcular_total_con_cantidad_cero() {
        $venta = new Venta();
        $this->assertEquals(0, $venta->calcularTotal(0, 500));
    }

    public function test_calcular_total_con_precio_cero() {
        $venta = new Venta();
        $this->assertEquals(0, $venta->calcularTotal(3, 0));
    }

    public function test_calcular_total_con_valores_negativos() {
        $venta = new Venta();
        $this->assertEquals(-1500, $venta->calcularTotal(3, -500));
    }
} 