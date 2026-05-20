// Devuelve el carrito en objeto (Array)
function getCarrito() {
    return JSON.parse(localStorage.getItem('carrito') || '[]');
}

function mostrarCarrito() {
    const contenedorCarrito = document.getElementById('carrito-contenedor');

    if(!contenedorCarrito) return; // Si no existe el contenedor (Resto de paginas que no sean la del carrito)
    
    contenedorCarrito.innerHTML = '';
    const carrito = getCarrito();

    if (carrito.length === 0) {
        contenedorCarrito.innerHTML = '<p>No hay productos en el carrito.</p>';
        return;
    }

    carrito.forEach(producto => {
        // Calcular subtotal
        const subtotal = producto.precio * producto.cantidad;
        total += subtotal;

        const div = document.createElement('div');
        div.classList.add('producto-carro');
        div.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h4>${producto.nombre}</h4>
            <p>Precio: $${producto.precio}</p>
            <p>Cantidad: ${producto.cantidad}</p>
            <button class="btn-eliminar" data-id="${producto.id}">Eliminar</button>
        `;
        // Eliminar producto del carrito
        div.querySelector('.btn-eliminar').addEventListener('click', function () {
            eliminarProductoDelCarrito(Number(this.dataset.id));
        });
        contenedorCarrito.appendChild(div);
    });

    // Mostrar el total
    const totalDiv = document.createElement('div');
    totalDiv.classList.add('carrito-total');
    totalDiv.innerHTML = `<strong>Total: $${total.toFixed(2)}</strong>`;
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
    } else {
        carrito.push({ id: producto.id, nombre: producto.nombre, precio: producto.precio, cantidad: 1, imagen: producto.imagen });
    }
    localStorage.setItem('carrito', JSON.stringify(carrito)); // Actualizar el carrito en localStorage
    mostrarMensaje('Producto añadido al carrito');
}

function eliminarProductoDelCarrito(id) {
    const carrito = getCarrito().filter(p => p.id !== id);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    mostrarCarrito(); // Mostra el carrito actualitzat
}

function mostrarMensaje(texto) {
    const mensaje = document.getElementById('mensaje-carrito');

    mensaje.textContent = texto;
    mensaje.style.display = 'block';

    setTimeout(() => {
        mensaje.style.display = 'none';
    }, 2000);
}

// Mostrar el carrito al cargar la pagina
document.addEventListener('DOMContentLoaded', () => {
    mostrarCarrito();
});