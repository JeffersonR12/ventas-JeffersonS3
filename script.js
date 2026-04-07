function calcular() {
    const cantInput = document.getElementById('cantidad');
    const precInput = document.getElementById('precio');
    const campoTotal = document.getElementById('total');

    const cantidad = parseFloat(cantInput.value);
    const precio = parseFloat(precInput.value);

   
    if (isNaN(cantidad) || cantidad <= 0) {
        alert("Error: La cantidad debe ser un número mayor a 0.");
        cantInput.focus();
        return;
    }

    if (isNaN(precio) || precio <= 0) {
        alert("Error: El precio unitario debe ser mayor a 0.");
        precInput.focus();
        return;
    }

    const resultado = cantidad * precio;
    campoTotal.value = "S/ " + resultado.toFixed(2);
}

function registrar() {
    const cliente = document.getElementById('cliente').value.trim();
    const producto = document.getElementById('producto').value.trim();
    const cantidad = document.getElementById('cantidad').value;
    const precio = document.getElementById('precio').value;
    const total = document.getElementById('total').value;

    if (cliente === "" || producto === "" || cantidad === "" || precio === "" || total === "") {
        alert("Atención: Debe completar todos los campos y calcular el total para proceder.");
        return;
    }

    // Si todo está correcto
    alert(`VENTA REGISTRADA\n-------------------\nCliente: ${cliente}\nProducto: ${producto}\nTotal: ${total}`);
    
    limpiarFormulario();
}

function limpiarFormulario() {
    document.getElementById('cliente').value = "";
    document.getElementById('producto').value = "";
    document.getElementById('cantidad').value = "";
    document.getElementById('precio').value = "";
    document.getElementById('total').value = "";
}