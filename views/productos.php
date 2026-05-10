<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="productos-page">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container-main">
        <div class="page-header">
            <h1>Catálogo de Productos</h1>
            <div class="page-actions">
                <input type="text" id="buscar-producto" placeholder="Buscar producto..." class="input-search">
                <select id="filtro-categoria" class="input-select">
                    <option value="">Todas las categorías</option>
                </select>
            </div>
        </div>
        
        <div class="productos-grid" id="productos-container">
            <p class="loading">Cargando productos...</p>
        </div>
    </div>
    
    <script src="public/js/utils.js"></script>
    <script src="public/js/productos.js"></script>
</body>
</html>
