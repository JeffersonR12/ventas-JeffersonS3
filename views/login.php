<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>VENTAS</h1>
                <p class="subtitle">Sistema de Gestión Tecnológico</p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="admin@ventas.com" 
                        required
                    >
                    <span class="input-focus"></span>
                </div>
                
                <div class="form-group">
                    <label for="contraseña">Contraseña</label>
                    <input 
                        type="password" 
                        id="contraseña" 
                        name="contraseña" 
                        placeholder="••••••••" 
                        required
                    >
                    <span class="input-focus"></span>
                </div>
                
                <button type="submit" class="btn-login">
                    <span class="btn-text">INGRESAR</span>
                    <span class="btn-glow"></span>
                </button>
            </form>
            
            <div class="login-footer">
                <p>Cuenta de prueba:</p>
                <p class="credentials">
                    <strong>Email:</strong> admin@ventas.com<br>
                    <strong>Contraseña:</strong> admin123
                </p>
            </div>
        </div>
        
        <div class="login-bg-animation">
            <div class="line line-1"></div>
            <div class="line line-2"></div>
            <div class="line line-3"></div>
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
        </div>
    </div>
</body>
</html>
