<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="venta-page">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container-main">
        <div class="page-header">
            <h1>Nueva Venta</h1>
        </div>
        
        <div class="venta-container">
            <div class="venta-form-section">
                <div class="card">
                    <div class="card-header">
                        <h2>Datos del Cliente</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-cliente">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cliente-nombre">Nombre del Cliente *</label>
                                    <input type="text" id="cliente-nombre" placeholder="Ej: Juan Pérez" required>
                                </div>
                                <div class="form-group">
                                    <label for="cliente-email">Correo Electrónico</label>
                                    <input type="email" id="cliente-email" placeholder="cliente@example.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cliente-telefono">Teléfono</label>
                                <input type="tel" id="cliente-telefono" placeholder="987654321">
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2>Agregar Productos</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-productos">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select id="categoria">
                                        <option value="">Seleccionar categoría...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="producto">Producto *</label>
                                    <select id="producto" required>
                                        <option value="">Seleccionar producto...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad *</label>
                                    <input type="number" id="cantidad" min="1" value="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="precio">Precio Unitario</label>
                                    <input type="number" id="precio" step="0.01" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="subtotal-item">Subtotal Item</label>
                                    <input type="number" id="subtotal-item" step="0.01" readonly>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="agregarProducto()">
                                <span>+ Agregar Producto</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="venta-summary-section">
                <div class="card sticky">
                    <div class="card-header">
                        <h2>Resumen de Venta</h2>
                    </div>
                    <div class="card-body">
                        <div id="items-container" class="items-list">
                            <p class="empty-state">Agrega productos para comenzar</p>
                        </div>
                        
                        <div class="separator"></div>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="subtotal" class="amount">S/ 0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>IGV (18%):</span>
                            <span id="igv" class="amount">S/ 0.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="total" class="amount">S/ 0.00</span>
                        </div>
                        
                        <button type="button" class="btn btn-success btn-block" onclick="registrarVenta()">
                            <span>Registrar Venta</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="public/js/utils.js"></script>
    <script src="public/js/venta.js"></script>
</body>
</html>
