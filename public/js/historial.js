/**
 * Script del Historial de Ventas
 */

document.addEventListener('DOMContentLoaded', () => {
    cargarHistorial();
    
    // Event listeners para filtros
    document.getElementById('buscar').addEventListener('keyup', filtrarHistorial);
    document.getElementById('fecha-filtro').addEventListener('change', filtrarHistorial);
});

let ventasOriginal = [];

/**
 * Cargar historial de ventas
 */
async function cargarHistorial() {
    try {
        const response = await request(`${API_URL}?pagina=venta&accion=obtener_ventas`);
        ventasOriginal = response;
        mostrarVentas(response);
    } catch (error) {
        console.error('Error cargando historial:', error);
    }
}

/**
 * Mostrar ventas en tabla
 */
function mostrarVentas(ventas) {
    const tabla = document.getElementById('tabla-historial');
    
    if (!ventas || ventas.length === 0) {
        tabla.innerHTML = '<tr><td colspan="9" class="text-center">No hay ventas registradas</td></tr>';
        return;
    }
    
    tabla.innerHTML = ventas.map(venta => `
        <tr>
            <td>#${venta.id}</td>
            <td>${venta.cliente_nombre}</td>
            <td>${venta.vendedor}</td>
            <td>${formatearMoneda(venta.subtotal)}</td>
            <td>${formatearMoneda(venta.igv)}</td>
            <td>${formatearMoneda(venta.total)}</td>
            <td>${formatearFecha(venta.fecha_venta)}</td>
            <td><span class="badge badge-${venta.estado}">${venta.estado}</span></td>
            <td>
                <button class="btn-action" onclick="verDetalles(${venta.id})">Ver</button>
            </td>
        </tr>
    `).join('');
    
    agregarEstilosAcciones();
}

/**
 * Filtrar historial
 */
function filtrarHistorial() {
    const busqueda = document.getElementById('buscar').value.toLowerCase();
    const fecha = document.getElementById('fecha-filtro').value;
    
    const filtrado = ventasOriginal.filter(venta => {
        const coincideNombre = venta.cliente_nombre.toLowerCase().includes(busqueda);
        const coincideFecha = !fecha || formatearFecha(venta.fecha_venta) === fecha;
        return coincideNombre && coincideFecha;
    });
    
    mostrarVentas(filtrado);
}

/**
 * Ver detalles de venta
 */
async function verDetalles(ventaId) {
    try {
        const response = await request(`${API_URL}?pagina=venta&accion=obtener_detalles&venta_id=${ventaId}`);
        
        const modal = document.getElementById('modal-detalles');
        const content = document.getElementById('detalles-content');
        
        const detallesHTML = response.map(item => `
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 15px; padding: 10px; border-bottom: 1px solid rgba(0,217,255,0.2);">
                <div>
                    <div style="color: var(--primary); font-weight: bold;">${item.nombre}</div>
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">${item.categoria}</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">Cantidad</div>
                    <div style="color: var(--primary); font-weight: bold;">${item.cantidad}</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">P.U.</div>
                    <div style="color: var(--success);">${formatearMoneda(item.precio_unitario)}</div>
                </div>
                <div style="text-align: right;">
                    <div style="color: var(--text-secondary); font-size: 0.85rem;">Subtotal</div>
                    <div style="color: var(--success); font-weight: bold;">${formatearMoneda(item.subtotal)}</div>
                </div>
            </div>
        `).join('');
        
        content.innerHTML = detallesHTML;
        modal.classList.add('active');
        
    } catch (error) {
        console.error('Error cargando detalles:', error);
    }
}

/**
 * Cerrar modal
 */
function cerrarModal() {
    const modal = document.getElementById('modal-detalles');
    modal.classList.remove('active');
}

/**
 * Imprimir venta
 */
function imprimirVenta() {
    window.print();
}

/**
 * Agregar estilos para botones de acción
 */
function agregarEstilosAcciones() {
    const style = document.querySelector('style[data-actions]') || document.createElement('style');
    style.setAttribute('data-actions', 'true');
    style.textContent = `
        .btn-action {
            padding: 6px 12px;
            background: linear-gradient(135deg, #00d9ff 0%, #00a8cc 100%);
            color: #0a0e27;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.4);
        }
    `;
    
    if (!document.querySelector('style[data-actions]')) {
        document.head.appendChild(style);
    }
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', (e) => {
    const modal = document.getElementById('modal-detalles');
    if (e.target === modal) {
        cerrarModal();
    }
});
