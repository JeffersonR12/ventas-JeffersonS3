<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="historial-page">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container-main">
        <div class="page-header">
            <h1>Historial de Ventas</h1>
            <div class="page-actions">
                <input type="text" id="buscar" placeholder="Buscar por cliente..." class="input-search">
                <input type="date" id="fecha-filtro" class="input-date">
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Subtotal</th>
                            <th>IGV</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-historial">
                        <tr>
                            <td colspan="9" class="text-center">Cargando...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal para detalles de venta -->
    <div id="modal-detalles" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalles de Venta</h2>
                <button class="btn-close" onclick="cerrarModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="detalles-content">
                    <p>Cargando detalles...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                <button class="btn btn-primary" onclick="imprimirVenta()">Imprimir</button>
            </div>
        </div>
    </div>
    
    <script src="public/js/utils.js"></script>
    <script src="public/js/historial.js"></script>
</body>
</html>
