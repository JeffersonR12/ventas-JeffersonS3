/**
 * Script de Catálogo de Productos
 */

document.addEventListener('DOMContentLoaded', async () => {
    await cargarProductos();
    await cargarCategorias();
    
    // Event listeners
    document.getElementById('buscar-producto').addEventListener('keyup', filtrarProductos);
    document.getElementById('filtro-categoria').addEventListener('change', filtrarProductos);
});

let productosOriginal = [];

/**
 * Cargar productos
 */
async function cargarProductos() {
    try {
        const response = await request(`${API_URL}?pagina=producto&accion=obtener_productos`);
        productosOriginal = response;
        mostrarProductos(response);
    } catch (error) {
        console.error('Error cargando productos:', error);
    }
}

/**
 * Cargar categorías
 */
async function cargarCategorias() {
    try {
        const response = await request(`${API_URL}?pagina=producto&accion=obtener_categorias`);
        const select = document.getElementById('filtro-categoria');
        
        response.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria;
            option.textContent = categoria;
            select.appendChild(option);
        });
        
    } catch (error) {
        console.error('Error cargando categorías:', error);
    }
}

/**
 * Mostrar productos
 */
function mostrarProductos(productos) {
    const container = document.getElementById('productos-container');
    
    if (!productos || productos.length === 0) {
        container.innerHTML = '<p class="loading">No hay productos disponibles</p>';
        return;
    }
    
    container.innerHTML = productos.map(producto => {
        const icono = obtenerIconoProducto(producto.categoria);
        const estado = producto.stock > 0 ? 'disponible' : 'agotado';
        
        return `
            <div class="producto-card">
                <div class="producto-image">${icono}</div>
                <div class="producto-info">
                    <div class="producto-categoria">${producto.categoria}</div>
                    <div class="producto-nombre">${producto.nombre}</div>
                    <div class="producto-descripcion">${producto.descripcion || 'Sin descripción'}</div>
                    <div class="producto-precio">${formatearMoneda(producto.precio_unitario)}</div>
                    <div class="producto-stock">
                        Stock: <strong class="stock-${estado}">${producto.stock} unidades</strong>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    agregarEstilosStock();
}

/**
 * Obtener icono según categoría
 */
function obtenerIconoProducto(categoria) {
    const iconos = {
        'Laptops': '💻',
        'Monitores': '🖥️',
        'Teclados': '⌨️',
        'Mouses': '🖱️',
        'Audífonos': '🎧',
        'Accesorios': '🔌',
        'Cables': '🔗',
        'Adaptadores': '🔄'
    };
    return iconos[categoria] || '📦';
}

/**
 * Filtrar productos
 */
function filtrarProductos() {
    const busqueda = document.getElementById('buscar-producto').value.toLowerCase();
    const categoria = document.getElementById('filtro-categoria').value;
    
    const filtrado = productosOriginal.filter(producto => {
        const coincideNombre = producto.nombre.toLowerCase().includes(busqueda);
        const coincideCategoria = !categoria || producto.categoria === categoria;
        return coincideNombre && coincideCategoria;
    });
    
    mostrarProductos(filtrado);
}

/**
 * Agregar estilos para stock
 */
function agregarEstilosStock() {
    const style = document.querySelector('style[data-stock]') || document.createElement('style');
    style.setAttribute('data-stock', 'true');
    style.textContent = `
        .stock-disponible {
            color: #00d084;
        }
        
        .stock-agotado {
            color: #ff4757;
        }
    `;
    
    if (!document.querySelector('style[data-stock]')) {
        document.head.appendChild(style);
    }
}
