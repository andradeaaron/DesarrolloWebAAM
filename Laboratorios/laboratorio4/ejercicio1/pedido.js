const productos = [
    { id: 1, nombre: "Empanadas de queso", categoria: "Salados", precio: 6, imagen: "empanadas.jpg" },
    { id: 2, nombre: "Papas fritas", categoria: "Salados", precio: 8, imagen: "papas.jpg" },
    { id: 3, nombre: "Hamburguesa clásica", categoria: "Salados", precio: 18, imagen: "hamburguesa.jpg" },
    { id: 4, nombre: "Sopa de pollo", categoria: "Platos", precio: 12, imagen: "sopa.jpg" },
    { id: 5, nombre: "Pollo al horno", categoria: "Platos", precio: 22, imagen: "pollo.jpg" },
    { id: 6, nombre: "Spaghetti carbonara", categoria: "Platos", precio: 20, imagen: "espagueti.jpg" },
    { id: 7, nombre: "Flan casero", categoria: "Postres", precio: 7, imagen: "flan.jpg" },
    { id: 8, nombre: "Tiramisú", categoria: "Postres", precio: 12, imagen: "tiramisu.jpg" },
    { id: 9, nombre: "Helado de vainilla", categoria: "Postres", precio: 8, imagen: "vainilla.jpg" }
];

const reglas = {
    nombre: {
        regex: /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,50}$/,
        mensaje: "Solo letras y espacios (3 a 50).",
        etiqueta: "el nombre"
    },
    celular: {
        regex: /^[67]\d{7}$/,
        mensaje: "8 dígitos, empieza con 6 o 7.",
        etiqueta: "el celular"
    },
    aula: {
        regex: /^[A-Z]-\d{1,3}$/,
        mensaje: "Formato letra-número, ej. A-12.",
        etiqueta: "el aula"
    }
};

const catalogo = document.querySelector("#catalogo");
const resumen = document.querySelector("#resumen");
const txtBuscar = document.querySelector("#buscar");
const selCategoria = document.querySelector("#categoria");
const detalle = document.querySelector("#detalle");
const spanTotal = document.querySelector("#total");
const spanContador = document.querySelector("#contador");
const btnVaciar = document.querySelector("#vaciar");
const formulario = document.querySelector("#formPedido");
const selEntrega = document.querySelector("#entrega");
const campoAula = document.querySelector("#campoAula");
const cajaErrores = document.querySelector("#errores");
const listaErrores = document.querySelector("#listaErrores");
const mensajes = document.querySelector("#mensajes");
const camposTexto = [
    document.querySelector("#nombre"),
    document.querySelector("#celular"),
    document.querySelector("#aula")
];

let pedido = [];

function mostrarProductos(lista) {
    catalogo.textContent = "";

    lista.forEach((p) => {
        const tarjeta = document.createElement("article");
        tarjeta.classList.add("producto");

        const imagen = document.createElement("img");
        imagen.src = `../images/${p.imagen}`;
        imagen.alt = p.nombre;

        const cuerpo = document.createElement("div");
        cuerpo.classList.add("cuerpo");

        const etiqueta = document.createElement("span");
        etiqueta.classList.add("etiqueta", `et-${p.categoria.toLowerCase()}`);
        etiqueta.textContent = p.categoria;

        const titulo = document.createElement("h4");
        titulo.textContent = p.nombre;

        const precio = document.createElement("div");
        precio.classList.add("precio");
        precio.textContent = `Bs ${p.precio.toFixed(2)}`;

        const acciones = document.createElement("div");
        acciones.classList.add("acciones");

        const boton = document.createElement("button");
        boton.type = "button";
        boton.classList.add("boton", "mini", "btn-agregar");
        boton.dataset.id = p.id;
        boton.textContent = "Agregar";

        acciones.appendChild(boton);
        cuerpo.appendChild(etiqueta);
        cuerpo.appendChild(titulo);
        cuerpo.appendChild(precio);
        cuerpo.appendChild(acciones);
        tarjeta.appendChild(imagen);
        tarjeta.appendChild(cuerpo);
        catalogo.appendChild(tarjeta);
    });

    if (lista.length === 0) {
        resumen.textContent = "No hay productos que coincidan con la búsqueda.";
        resumen.className = "aviso aviso-error";
    } else {
        resumen.textContent = `Mostrando ${lista.length} de ${productos.length} productos`;
        resumen.className = "vacio";
    }
}

function filtrar() {
    const texto = txtBuscar.value.trim().toLowerCase();
    const categoria = selCategoria.value;

    const resultado = productos.filter((p) => {
        const coincideNombre = p.nombre.toLowerCase().includes(texto);
        const coincideCategoria = categoria === "Todas" || p.categoria === categoria;
        return coincideNombre && coincideCategoria;
    });

    mostrarProductos(resultado);
}

function guardarPedido() {
    localStorage.setItem("pedidoCafeteria", JSON.stringify(pedido));
}

function recuperarPedido() {
    try {
        const guardado = JSON.parse(localStorage.getItem("pedidoCafeteria"));
        if (!Array.isArray(guardado)) {
            return [];
        }
        return guardado
            .map((item) => {
                const producto = productos.find((p) => p.id === item.id);
                const cantidad = Number(item.cantidad);
                if (!producto || !Number.isInteger(cantidad) || cantidad < 1) {
                    return null;
                }
                return { id: producto.id, nombre: producto.nombre, precio: producto.precio, cantidad };
            })
            .filter((item) => item !== null);
    } catch (error) {
        return [];
    }
}

function calcularTotal() {
    return pedido.reduce((acumulado, item) => acumulado + item.precio * item.cantidad, 0);
}

function dibujarPedido() {
    if (pedido.length === 0) {
        detalle.innerHTML = '<tr><td colspan="4" class="vacio">Aún no agregó productos.</td></tr>';
    } else {
        detalle.innerHTML = pedido
            .map((item) => `
                <tr>
                    <td>${item.nombre}</td>
                    <td class="num">${item.cantidad}</td>
                    <td class="num">Bs ${(item.precio * item.cantidad).toFixed(2)}</td>
                    <td><button type="button" class="boton boton-no mini btn-quitar" data-id="${item.id}">−</button></td>
                </tr>`)
            .join("");
    }

    spanTotal.textContent = calcularTotal().toFixed(2);
    spanContador.textContent = pedido.reduce((acumulado, item) => acumulado + item.cantidad, 0);
}

function actualizarPedido() {
    dibujarPedido();
    guardarPedido();
}

function agregarAlPedido(id) {
    const producto = productos.find((p) => p.id === id);
    if (!producto) {
        return;
    }

    const existente = pedido.find((item) => item.id === id);
    if (existente) {
        existente.cantidad++;
    } else {
        pedido.push({ id: producto.id, nombre: producto.nombre, precio: producto.precio, cantidad: 1 });
    }
    actualizarPedido();
}

function quitarDelPedido(id) {
    const indice = pedido.findIndex((item) => item.id === id);
    if (indice === -1) {
        return;
    }

    if (pedido[indice].cantidad > 1) {
        pedido[indice].cantidad--;
    } else {
        pedido.splice(indice, 1);
    }
    actualizarPedido();
}

function validarCampo(campo) {
    const regla = reglas[campo.name];
    const correcto = regla.regex.test(campo.value.trim());
    campo.classList.toggle("invalido", !correcto);
    campo.classList.toggle("valido", correcto);
    document.querySelector(`#error-${campo.id}`).textContent = correcto ? "" : regla.mensaje;
    return correcto;
}

function limpiarValidacion() {
    camposTexto.forEach((campo) => {
        campo.classList.remove("invalido", "valido");
        document.querySelector(`#error-${campo.id}`).textContent = "";
    });
    cajaErrores.classList.add("oculto");
    listaErrores.textContent = "";
}

function mostrarErrores(problemas) {
    listaErrores.textContent = "";
    problemas.forEach((problema) => {
        const li = document.createElement("li");
        li.textContent = problema;
        listaErrores.appendChild(li);
    });
    cajaErrores.classList.remove("oculto");
}

function aulaVisible() {
    return selEntrega.value === "Llevar al aula";
}

function confirmarPedido(datos) {
    const numero = Math.floor(10000 + Math.random() * 90000);
    const total = calcularTotal().toFixed(2);
    const destino = aulaVisible() ? `Entrega en aula ${datos.aula}.` : "Recoger en caja.";

    const aviso = document.createElement("div");
    aviso.classList.add("aviso");
    aviso.textContent = `Pedido N.º ${numero} confirmado para ${datos.nombre} (${datos.celular}). Total Bs ${total}. ${destino}`;
    mensajes.textContent = "";
    mensajes.appendChild(aviso);

    console.log(JSON.stringify({ numero, cliente: datos, productos: pedido, total: Number(total) }));

    pedido = [];
    actualizarPedido();
    formulario.reset();
    campoAula.classList.add("oculto");
    limpiarValidacion();
}

document.addEventListener("DOMContentLoaded", () => {
    pedido = recuperarPedido();
    mostrarProductos(productos);
    dibujarPedido();
});

txtBuscar.addEventListener("input", filtrar);
selCategoria.addEventListener("change", filtrar);

catalogo.addEventListener("click", (e) => {
    if (e.target.matches(".btn-agregar")) {
        agregarAlPedido(Number(e.target.dataset.id));
    }
});

detalle.addEventListener("click", (e) => {
    if (e.target.matches(".btn-quitar")) {
        quitarDelPedido(Number(e.target.dataset.id));
    }
});

btnVaciar.addEventListener("click", () => {
    pedido = [];
    actualizarPedido();
});

selEntrega.addEventListener("change", () => {
    campoAula.classList.toggle("oculto", !aulaVisible());
    if (!aulaVisible()) {
        const aula = document.querySelector("#aula");
        aula.value = "";
        aula.classList.remove("invalido", "valido");
        document.querySelector("#error-aula").textContent = "";
    }
});

formulario.addEventListener("input", (e) => {
    if (e.target.matches("#nombre, #celular, #aula") && (e.target.classList.contains("invalido") || e.target.classList.contains("valido"))) {
        validarCampo(e.target);
    }
});

formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    const problemas = [];
    if (pedido.length === 0) {
        problemas.push("El pedido está vacío.");
    }

    const camposActivos = aulaVisible() ? camposTexto : camposTexto.slice(0, 2);
    camposActivos.forEach((campo) => {
        if (!validarCampo(campo)) {
            problemas.push(`Revise ${reglas[campo.name].etiqueta}.`);
        }
    });

    if (problemas.length > 0) {
        mostrarErrores(problemas);
        return;
    }

    cajaErrores.classList.add("oculto");
    confirmarPedido(Object.fromEntries(new FormData(formulario)));
});
