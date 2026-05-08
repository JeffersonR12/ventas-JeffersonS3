/**
 * Script para crear ventas
 */

const IGV_RATE = 0.18;
let productosActuales = [];
let carrito = [];

document.addEventListener('DOMContentLoaded', async () => {
    await cargarCategorias();
    await cargarProductos();
    
    // Event listeners
    document.getElementById('categoria').addEventListener('change', filtrarProductosPorCategoria);
    document.getElementById('producto').addEventListener('change', actualizarPrecioProducto);
    document.getElementById('cantidad').addEventListener('change', actualizarSubtotalItem);
});

/**
 * Cargar categorías
 */
async function cargarCategorias() {
    try {
        const response = await request(`${API_URL}?pagina=producto&accion=obtener_categorias`);
        const select = document.getElementById('categoria');
        
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
 * Cargar todos los productos
 */
async function cargarProductos() {
    try {
        const response = await request(`${API_URL}?pagina=producto&accion=obtener_productos`);
        productosActuales = response;
        llenarSelectProductos(response);
    } catch (error) {
        console.error('Error cargando productos:', error);
    }
}

/**
 * Llenar select de productos
 */
function llenarSelectProductos(productos) {
    const select = document.getElementById('producto');
    select.innerHTML = '<option value="">Seleccionar producto...</option>';
    
    productos.forEach(producto => {
        const option = document.createElement('option');
        option.value = producto.id;
        option.textContent = `${producto.nombre} - Stock: ${producto.stock}`;
        option.dataset.precio = producto.precio_unitario;
        option.dataset.stock = producto.stock;
        select.appendChild(option);
    });
}

/**
 * Filtrar productos por categoría
 */
async function filtrarProductosPorCategoria() {
    const categoria = document.getElementById('categoria').value;
    
    if (!categoria) {
        llenarSelectProductos(productosActuales);
        return;
    }
    
    try {
        const response = await request(`${API_URL}?pagina=producto&accion=obtener_por_categoria&categoria=${encodeURIComponent(categoria)}`);
        llenarSelectProductos(response);
    } catch (error) {
        console.error('Error filtrando productos:', error);
    }
}

/**
 * Actualizar precio del producto
 */
function actualizarPrecioProducto() {
    const select = document.getElementById('producto');
    const option = select.options[select.selectedIndex];
    const precio = option.dataset.precio || 0;
    const stock = option.dataset.stock || 0;
    
    document.getElementById('precio').value = parseFloat(precio).toFixed(2);
    document.getElementById('cantidad').max = stock;
    document.getElementById('cantidad').value = 1;
    
    actualizarSubtotalItem();
}

/**
 * Actualizar subtotal del item
 */
function actualizarSubtotalItem() {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precio = parseFloat(document.getElementById('precio').value) || 0;
    const subtotal = cantidad * precio;
    
    document.getElementById('subtotal-item').value = subtotal.toFixed(2);
}

/**
 * Agregar producto al carrito
 */
function agregarProducto() {
    const productoId = document.getElementById('producto').value;
    const cantidad = parseInt(document.getElementById('cantidad').value) || 0;
    const precio = parseFloat(document.getElementById('precio').value) || 0;
    
    if (!productoId) {
        mostrarNotificacion('Selecciona un producto', 'error');
        return;
    }
    
    if (cantidad <= 0) {
        mostrarNotificacion('La cantidad debe ser mayor a 0', 'error');
        return;
    }
    
    // Buscar producto
    const producto = productosActuales.find(p => p.id == productoId);
    if (!producto) {
        mostrarNotificacion('Producto no encontrado', 'error');
        return;
    }
    
    // Verificar stock
    if (cantidad > producto.stock) {
        mostrarNotificacion(`Stock insuficiente. Disponible: ${producto.stock}`, 'error');
        return;
    }
    
    // Verificar si el producto ya está en el carrito
    const existe = carrito.find(item => item.producto_id == productoId);
    
    if (existe) {
        // Actualizar cantidad
        const nuevaCantidad = existe.cantidad + cantidad;
        if (nuevaCantidad > producto.stock) {
            mostrarNotificacion(`Stock insuficiente. Máximo: ${producto.stock}`, 'error');
            return;
        }
        existe.cantidad = nuevaCantidad;
    } else {
        // Agregar nuevo item
        carrito.push({
            producto_id: productoId,
            nombre: producto.nombre,
            categoria: producto.categoria,
            cantidad: cantidad,
            precio_unitario: precio
        });
    }
    
    actualizarCarrito();
    limpiarFormulario();
    mostrarNotificacion(`${producto.nombre} agregado al carrito`, 'success');
}

/**
 * Actualizar vista del carrito
 */
function actualizarCarrito() {
    const container = document.getElementById('items-container');
    
    if (carrito.length === 0) {
        container.innerHTML = '<p class="empty-state">Agrega productos para comenzar</p>';
        actualizarTotales(0, 0, 0);
        return;
    }
    
    container.innerHTML = carrito.map((item, index) => `
        <div class="item-row">
            <div class="item-info">
                <div class="item-name">${item.nombre}</div>
                <div class="item-qty">${item.cantidad} x ${formatearMoneda(item.precio_unitario)}</div>
            </div>
            <span class="item-price">${formatearMoneda(item.cantidad * item.precio_unitario)}</span>
            <button class="btn-remove" onclick="eliminarDelCarrito(${index})">✕</button>
        </div>
    `).join('');
    
    // Calcular totales
    const subtotal = carrito.reduce((sum, item) => sum + (item.cantidad * item.precio_unitario), 0);
    const igv = subtotal * IGV_RATE;
    const total = subtotal + igv;
    
    actualizarTotales(subtotal, igv, total);
}

/**
 * Actualizar totales
 */
function actualizarTotales(subtotal, igv, total) {
    document.getElementById('subtotal').textContent = formatearMoneda(subtotal);
    document.getElementById('igv').textContent = formatearMoneda(igv);
    document.getElementById('total').textContent = formatearMoneda(total);
}

/**
 * Eliminar del carrito
 */
function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarCarrito();
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    document.getElementById('categoria').value = '';
    document.getElementById('producto').value = '';
    document.getElementById('cantidad').value = 1;
    document.getElementById('precio').value = '';
    document.getElementById('subtotal-item').value = '';
}

/**
 * Registrar venta
 */
async function registrarVenta() {
    const clienteNombre = document.getElementById('cliente-nombre').value.trim();
    const clienteEmail = document.getElementById('cliente-email').value.trim();
    const clienteTelefono = document.getElementById('cliente-telefono').value.trim();
    
    if (!clienteNombre) {
        mostrarNotificacion('Nombre del cliente es requerido', 'error');
        return;
    }
    
    if (carrito.length === 0) {
        mostrarNotificacion('Agrega al menos un producto', 'error');
        return;
    }
    
    try {
        const response = await request(`${API_URL}?pagina=venta&accion=crear`, {
            method: 'POST',
            body: {
                cliente_nombre: clienteNombre,
                cliente_email: clienteEmail,
                cliente_telefono: clienteTelefono,
                items: carrito
            }
        });
        
        if (response.exito) {
            mostrarNotificacion('¡Venta registrada exitosamente!', 'success');
            
            // Limpiar formulario
            document.getElementById('form-cliente').reset();
            carrito = [];
            actualizarCarrito();
            
            // Redirigir después de 2 segundos
            setTimeout(() => {
                window.location.href = `${API_URL}?pagina=historial`;
            }, 2000);
        } else {
            mostrarNotificacion(response.mensaje || 'Error al registrar venta', 'error');
        }
    } catch (error) {
        mostrarNotificacion('Error al registrar venta: ' + error.message, 'error');
    }
}
