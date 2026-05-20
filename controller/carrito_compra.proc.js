function mostrarCarrito() {
    const carrito = getCarrito();
    const contenedorCarrito = document.getElementById('carrito-contenedor');
    contenedorCarrito.innerHTML = '';
    if (carrito.length === 0) {
        contenedorCarrito.innerHTML = '<p>No hay productos en el carrito.</p>';
    } else {
        carrito.forEach(producto => {
        const div = document.createElement('div');
        div.classList.add('producto-carro');
        div.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h4>${producto.nombre}</h4>
            <p>Precio: $${producto.precio}</p>
            <p>Cantidad: ${producto.cantidad}</p>
            <button class="btn-eliminar" data-id="${producto.id}">Eliminar</button>
        `;
        contenedorCarrito.appendChild(div);
    });
    }
}

// Devuelve el carrito en objeto (Array)
function getCarrito() {
    return JSON.parse(localStorage.getItem('carrito') || '[]');
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

function mostrarMensaje(texto) {

    const mensaje = document.getElementById('mensaje-carrito');

    mensaje.textContent = texto;
    mensaje.style.display = 'block';

    setTimeout(() => {
        mensaje.style.display = 'none';
    }, 2000);
}

// Enviar al checkout
async function checkout(email) {
  const res = await fetch('/api/checkout.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, carrito: getCarrito() })
  });
  const data = await res.json();
  if (data.ok) {
    localStorage.removeItem('carrito'); // Limpiar el carrito
  }
}