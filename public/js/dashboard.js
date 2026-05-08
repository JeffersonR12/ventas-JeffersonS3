/**
 * Script del Dashboard
 */

document.addEventListener('DOMContentLoaded', async () => {
    cargarVentasDelDia();
    cargarEstadisticas();
});

/**
 * Cargar ventas del día
 */
async function cargarVentasDelDia() {
    try {
        const response = await request(`${API_URL}?pagina=venta&accion=obtener_ventas`);
        const tabla = document.getElementById('tabla-ventas');
        
        if (!response || response.length === 0) {
            tabla.innerHTML = '<tr><td colspan="5" class="text-center">Sin ventas hoy</td></tr>';
            return;
        }
        
        tabla.innerHTML = response.slice(0, 5).map(venta => `
            <tr>
                <td>#${venta.id}</td>
                <td>${venta.cliente_nombre}</td>
                <td>${formatearMoneda(venta.total)}</td>
                <td>${formatearFecha(venta.fecha_venta)} ${formatearHora(venta.fecha_venta)}</td>
                <td><span class="badge badge-${venta.estado}">${venta.estado}</span></td>
            </tr>
        `).join('');
        
        document.getElementById('ventas-hoy').textContent = response.length;
        
    } catch (error) {
        console.error('Error cargando ventas:', error);
    }
}

/**
 * Cargar estadísticas
 */
async function cargarEstadisticas() {
    try {
        const response = await request(`${API_URL}?pagina=venta&accion=estadisticas`);
        
        if (response) {
            document.getElementById('ingresos-hoy').textContent = formatearMoneda(response.total_ingresos || 0);
        }
        
    } catch (error) {
        console.error('Error cargando estadísticas:', error);
    }
}

// Agregar estilos para badges
const style = document.createElement('style');
style.textContent = `
    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .badge-completado {
        background: rgba(0, 208, 132, 0.2);
        color: #00d084;
    }
    
    .badge-pendiente {
        background: rgba(255, 165, 2, 0.2);
        color: #ffa502;
    }
    
    .badge-cancelado {
        background: rgba(255, 71, 87, 0.2);
        color: #ff4757;
    }
`;
document.head.appendChild(style);
