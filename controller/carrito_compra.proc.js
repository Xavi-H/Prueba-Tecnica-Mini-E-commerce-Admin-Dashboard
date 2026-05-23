// Devuelve el carrito en objeto (Array)
function getCarrito() {
    return JSON.parse(localStorage.getItem('carrito') || '[]');
}

// Guardar el carrito actualizado
function guardarCarrito(carrito){
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// Mostra els productes del carrito a la pagina carrito_compra.php
function mostrarCarrito() {
    const contenedorCarrito = document.getElementById('carrito-contenedor');
    if(!contenedorCarrito) return; // Si no existe el contenedor (Resto de paginas que no sean carrito_compra.php)
    
    const carrito = getCarrito();
    contenedorCarrito.innerHTML = '';

    if (carrito.length === 0) {
        contenedorCarrito.innerHTML = '<p>No hay productos en el carrito.</p>';
        return;
    }

    let precioTotal = 0;
    carrito.forEach(producto => {
        // Calcular subtotal de un producto
        const subtotal = producto.precio * producto.cantidad;
        precioTotal += subtotal;

        const div = document.createElement('div');
        div.classList.add('producto-carro');
        div.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h4>${producto.nombre}</h4>
            <p>Precio: ${producto.precio}€</p>
            <p>Cantidad: ${producto.cantidad}</p>
            <button class="btn-eliminar">Eliminar</button>
        `;
        // Boton de eliminar producto del carrito
        div.querySelector('.btn-eliminar').addEventListener('click', function () {
            eliminarProductoDelCarrito(producto.id);
        });
        contenedorCarrito.appendChild(div);
    });

    // Mostrar el precio total
    const totalDiv = document.createElement('div');
    totalDiv.classList.add('carrito-total');
    totalDiv.innerHTML = `<strong>Total: ${precioTotal.toFixed(2)}€</strong>`;
    contenedorCarrito.appendChild(totalDiv);
}

/**
 * Añadir producto al carrito
 * Comprueba si el producto ya existe en el carrito y le suma 1 en cantidad
 */ 
function addProductoAlCarrito(producto) {
    const carrito = getCarrito();
    const productoExistente = carrito.find(p => p.id === producto.id);
    if (productoExistente) {
        productoExistente.cantidad += 1;
        mostrarMensaje('Cantidad actualizada en el carrito correctamente');
    } else {
        carrito.push({ id: producto.id, nombre: producto.nombre, precio: producto.precio, cantidad: 1, imagen: producto.imagen });
        mostrarMensaje('Producto añadido al carrito correctamente');
    }
    localStorage.setItem('carrito', JSON.stringify(carrito)); // Actualizar el carrito en localStorage
}

function eliminarProductoDelCarrito(id) {
    const carrito = getCarrito().filter(p => p.id !== id);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    mostrarCarrito(); // Actualitzar el carrito en la pagina
}

function mostrarMensaje(texto) {
    const mensaje = document.getElementById('mensaje-carrito');

    mensaje.innerHTML = texto;
    mensaje.style.display = 'block';
    setTimeout(() => {
        mensaje.style.display = 'none';
    }, 15000); // Ocultar mensaje después de 15 segundos
}

// Mostrar el carrito al cargar la pagina
document.addEventListener('DOMContentLoaded', () => {
    mostrarCarrito();
});