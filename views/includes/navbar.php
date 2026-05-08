<!-- Barra de navegación -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <h1>🎮 VENTAS</h1>
        </div>
        
        <ul class="navbar-menu">
            <li><a href="index.php?pagina=dashboard" class="nav-link">Dashboard</a></li>
            <li><a href="index.php?pagina=venta" class="nav-link">Nueva Venta</a></li>
            <li><a href="index.php?pagina=historial" class="nav-link">Historial</a></li>
            <li><a href="index.php?pagina=productos" class="nav-link">Productos</a></li>
        </ul>
        
        <div class="navbar-user">
            <span class="user-name"><?php echo $_SESSION['usuario_nombre']; ?></span>
            <a href="controllers/LoginController.php?accion=logout" class="btn btn-logout">Salir</a>
        </div>
    </div>
</nav>
