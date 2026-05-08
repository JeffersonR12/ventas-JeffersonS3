<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="dashboard-page">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container-main">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p class="subtitle">Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <h3>Ventas Hoy</h3>
                    <p class="stat-value" id="ventas-hoy">0</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <h3>Ingresos Hoy</h3>
                    <p class="stat-value" id="ingresos-hoy">S/ 0.00</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <h3>Productos</h3>
                    <p class="stat-value" id="total-productos"><?php echo count($productos); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3>Clientes Este Mes</h3>
                    <p class="stat-value" id="clientes-mes">0</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">
                    <h2>Últimas Ventas</h2>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-ventas">
                            <tr>
                                <td colspan="5" class="text-center">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>Acciones Rápidas</h2>
                </div>
                <div class="card-body actions">
                    <a href="index.php?pagina=venta" class="btn btn-primary">
                        <span class="icon">➕</span>
                        <span>Nueva Venta</span>
                    </a>
                    <a href="index.php?pagina=historial" class="btn btn-secondary">
                        <span class="icon">📋</span>
                        <span>Ver Historial</span>
                    </a>
                    <a href="index.php?pagina=productos" class="btn btn-tertiary">
                        <span class="icon">📦</span>
                        <span>Productos</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="public/js/dashboard.js"></script>
</body>
</html>
