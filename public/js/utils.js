/**
 * Funciones compartidas del sistema
 */

const API_URL = 'index.php';

/**
 * Realizar petición AJAX
 */
async function request(url, options = {}) {
    try {
        const response = await fetch(url, {
            method: options.method || 'GET',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            body: options.body ? JSON.stringify(options.body) : null
        });

        const text = await response.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch (parseError) {
            // Si no es JSON válido, mantenemos el texto crudo para depuración.
            console.warn('Respuesta no es JSON válido:', text);
        }

        if (!response.ok) {
            const message = data && data.mensaje ? data.mensaje : `HTTP error! status: ${response.status}`;
            throw new Error(message);
        }

        return data;
    } catch (error) {
        console.error('Error en petición:', error);
        mostrarNotificacion('Error en la petición: ' + error.message, 'error');
        throw error;
    }
}

/**
 * Mostrar notificación
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion notificacion-${tipo}`;
    notificacion.textContent = mensaje;
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${tipo === 'error' ? '#ff4757' : tipo === 'success' ? '#00d084' : '#00d9ff'};
        color: ${tipo === 'success' || tipo === 'error' ? 'white' : '#0a0e27'};
        border-radius: 5px;
        z-index: 2000;
        font-weight: bold;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notificacion.remove(), 300);
    }, 3000);
}

/**
 * Formatear moneda
 */
function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
    }).format(valor).replace('PEN', 'S/').trim();
}

/**
 * Formatear fecha
 */
function formatearFecha(fecha) {
    return new Intl.DateTimeFormat('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).format(new Date(fecha));
}

/**
 * Formatear hora
 */
function formatearHora(fecha) {
    return new Intl.DateTimeFormat('es-PE', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    }).format(new Date(fecha));
}

/**
 * Agregar estilos de animación
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(300px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(300px);
        }
    }
`;
document.head.appendChild(style);
