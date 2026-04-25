function calcularTotalVenta() {
    const cantidadInput = document.getElementById('cantidad');
    const precioInput = document.getElementById('precio');
    const totalInput = document.getElementById('total');

    const cantidad = parseFloat(cantidadInput.value);
    const precio = parseFloat(precioInput.value);

    if (!esCantidadValida(cantidad)) {
        mostrarMensajeError("La cantidad debe ser un número mayor a 0.");
        cantidadInput.focus();
        return;
    }

    if (!esPrecioValido(precio)) {
        mostrarMensajeError("El precio unitario debe ser mayor a 0.");
        precioInput.focus();
        return;
    }

    const total = cantidad * precio;
    totalInput.value = "S/ " + total.toFixed(2);
}

function esCantidadValida(cantidad) {
    return !isNaN(cantidad) && cantidad > 0;
}

function esPrecioValido(precio) {
    return !isNaN(precio) && precio > 0;
}

function mostrarMensajeError(mensaje) {
    alert(mensaje); // Temporalmente, luego cambiar a un div
}

function registrarVenta() {
    const datosVenta = obtenerDatosFormulario();

    if (!sonCamposCompletos(datosVenta)) {
        mostrarMensajeError("Debe completar todos los campos y calcular el total para proceder.");
        return;
    }

    enviarDatosAlServidor(datosVenta);
}

function obtenerDatosFormulario() {
    return {
        cliente: document.getElementById('cliente').value.trim(),
        producto: document.getElementById('producto').value.trim(),
        cantidad: document.getElementById('cantidad').value,
        precio: document.getElementById('precio').value,
        total: document.getElementById('total').value.replace("S/ ", "")
    };
}

function sonCamposCompletos(datos) {
    return Object.values(datos).every(campo => campo !== "");
}

function enviarDatosAlServidor(datos) {
    fetch('guardar_venta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(datos)
    })
    .then(response => response.text())
    .then(resultado => {
        if (resultado === "Éxito") {
            mostrarMensajeExito(`VENTA REGISTRADA\n-------------------\nCliente: ${datos.cliente}\nProducto: ${datos.producto}\nTotal: S/ ${datos.total}`);
            limpiarCamposFormulario();
        } else {
            mostrarMensajeError("Error al registrar la venta: " + resultado);
        }
    })
    .catch(error => {
        mostrarMensajeError("Error de conexión: " + error.message);
    });
}

function mostrarMensajeExito(mensaje) {
    alert(mensaje); // Temporalmente
}

function limpiarCamposFormulario() {
    document.getElementById('cliente').value = "";
    document.getElementById('producto').value = "";
    document.getElementById('cantidad').value = "";
    document.getElementById('precio').value = "";
    document.getElementById('total').value = "";
}