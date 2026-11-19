const contenido = document.querySelector("#contenido");
const avisos = document.querySelector("#avisos");
const modal = document.querySelector("#modal");
const modalTitulo = document.querySelector("#modalTitulo");
const modalCuerpo = document.querySelector("#modalCuerpo");
const modalPie = document.querySelector("#modalPie");
const botonesMenu = document.querySelectorAll("nav button[data-vista]");

let temporizador;
let numeroPeticion = 0;

function escapar(valor) {
    return String(valor)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function mostrarAviso(mensaje, tipo = "ok") {
    const aviso = document.createElement("div");
    aviso.classList.add("aviso");
    if (tipo === "error") {
        aviso.classList.add("aviso-error");
    }
    aviso.textContent = mensaje;
    avisos.textContent = "";
    avisos.appendChild(aviso);
}

async function cargarVista(url) {
    avisos.textContent = "";
    contenido.innerHTML = '<p class="cargando">Cargando...</p>';
    try {
        const resp = await fetch(url);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        contenido.innerHTML = await resp.text();
    } catch (error) {
        contenido.innerHTML = `<div class="aviso aviso-error">${escapar(error.message)}</div>`;
    }
}

async function cargarCategorias(select) {
    const resp = await fetch("categorias.php");
    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
    const categorias = await resp.json();
    categorias.forEach((c) => {
        const opcion = document.createElement("option");
        opcion.value = c.id;
        opcion.textContent = c.nombre;
        select.appendChild(opcion);
    });
}

function tarjetaProducto(p) {
    const imagen = p.imagen
        ? `<img src="../images/${escapar(p.imagen)}" alt="${escapar(p.nombre)}">`
        : '<div class="sin-imagen">SIN FOTO</div>';
    const agotado = p.disponible ? "" : '<span class="etiqueta et-agotado">Agotado</span>';

    return `
        <article class="producto${p.disponible ? "" : " agotado"}" data-id="${p.id}">
            ${imagen}
            <div class="cuerpo">
                <span class="etiqueta et-${escapar(p.categoria.toLowerCase())}">${escapar(p.categoria)}</span>
                <h4>${escapar(p.nombre)}</h4>
                <div class="precio">Bs ${p.precio.toFixed(2)}</div>
                ${agotado}
                <div class="acciones">
                    <button type="button" class="boton mini btn-detalle" data-id="${p.id}">Detalle</button>
                    <button type="button" class="boton boton-no mini btn-eliminar" data-id="${p.id}" data-nombre="${escapar(p.nombre)}">Eliminar</button>
                </div>
            </div>
        </article>`;
}

async function cargarProductos() {
    const txtBuscar = document.querySelector("#buscar");
    const selCategoria = document.querySelector("#filtroCategoria");
    const grilla = document.querySelector("#grilla");
    const contador = document.querySelector("#contador");
    if (!txtBuscar || !selCategoria || !grilla || !contador) return;

    const peticion = ++numeroPeticion;
    const params = new URLSearchParams({ buscar: txtBuscar.value.trim(), categoria: selCategoria.value });
    grilla.innerHTML = '<p class="cargando">Cargando productos...</p>';

    try {
        const resp = await fetch(`productos.php?${params}`);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const productos = await resp.json();
        if (peticion !== numeroPeticion || !document.body.contains(grilla)) return;
        contador.textContent = productos.length;
        grilla.innerHTML = productos.length > 0
            ? productos.map(tarjetaProducto).join("")
            : '<p class="vacio">No se encontraron productos.</p>';
    } catch (error) {
        if (peticion !== numeroPeticion || !document.body.contains(grilla)) return;
        grilla.innerHTML = `<div class="aviso aviso-error">${escapar(error.message)}</div>`;
    }
}

async function iniciarMenu() {
    avisos.textContent = "";
    contenido.innerHTML = `
        <h2>Menú <span class="contador" id="contador">0</span></h2>
        <div class="barra">
            <div class="campo">
                <label for="buscar">Buscar (mientras escribe)</label>
                <input type="search" id="buscar" placeholder="Ej.: pollo" autocomplete="off">
            </div>
            <div class="campo">
                <label for="filtroCategoria">Categoría</label>
                <select id="filtroCategoria">
                    <option value="0">Todas</option>
                </select>
            </div>
        </div>
        <div class="grilla" id="grilla"></div>`;

    const txtBuscar = document.querySelector("#buscar");
    const selCategoria = document.querySelector("#filtroCategoria");

    txtBuscar.addEventListener("input", () => {
        clearTimeout(temporizador);
        temporizador = setTimeout(cargarProductos, 300);
    });
    selCategoria.addEventListener("change", cargarProductos);

    try {
        await cargarCategorias(selCategoria);
    } catch (error) {
        mostrarAviso(`No se pudieron cargar las categorías: ${error.message}`, "error");
    }
    await cargarProductos();
}

async function iniciarAlta() {
    await cargarVista("form_producto.html");
    const select = document.querySelector("#categoria");
    if (!select) return;
    try {
        await cargarCategorias(select);
    } catch (error) {
        mostrarAviso(`No se pudieron cargar las categorías: ${error.message}`, "error");
    }
}

function abrirModal(titulo, cuerpoHtml, pieHtml) {
    modalTitulo.textContent = titulo;
    modalCuerpo.innerHTML = cuerpoHtml;
    modalPie.innerHTML = pieHtml;
    modal.classList.add("abierto");
}

function cerrarModal() {
    modal.classList.remove("abierto");
}

async function verDetalle(id) {
    try {
        const resp = await fetch("producto.php?id=" + encodeURIComponent(id));
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const p = await resp.json();

        const imagen = p.imagen
            ? `<img src="../images/${escapar(p.imagen)}" alt="${escapar(p.nombre)}">`
            : '<div class="sin-imagen">SIN FOTO</div>';
        const agotado = p.disponible ? "" : ' <span class="etiqueta et-agotado">Agotado</span>';
        const descripcion = p.descripcion ? escapar(p.descripcion) : "Sin descripción.";

        abrirModal(
            p.nombre,
            `${imagen}
             <p><span class="etiqueta et-${escapar(p.categoria.toLowerCase())}">${escapar(p.categoria)}</span>${agotado}</p>
             <p>${descripcion}</p>
             <p>Precio: Bs ${p.precio.toFixed(2)}</p>`,
            '<button type="button" class="boton boton-sec" data-accion="cerrar">Cerrar</button>'
        );
    } catch (error) {
        mostrarAviso(`No se pudo cargar el detalle: ${error.message}`, "error");
    }
}

function pedirConfirmacion(id, nombre) {
    abrirModal(
        "Confirmar eliminación",
        `<p>¿Está seguro de eliminar <strong>${escapar(nombre)}</strong>? Esta acción no se puede deshacer.</p>`,
        `<button type="button" class="boton boton-sec" data-accion="cerrar">Cancelar</button>
         <button type="button" class="boton boton-no" data-accion="eliminar" data-id="${escapar(id)}">Eliminar</button>`
    );
}

async function eliminarProducto(id, boton) {
    const datos = new FormData();
    datos.append("id", id);
    boton.disabled = true;

    try {
        const resp = await fetch("eliminar.php", { method: "POST", body: datos });
        const resultado = await resp.json();
        if (!resp.ok) throw new Error(resultado.mensaje || `HTTP ${resp.status}`);

        const tarjeta = document.querySelector(`.producto[data-id="${id}"]`);
        if (tarjeta) {
            tarjeta.remove();
            const contador = document.querySelector("#contador");
            const grilla = document.querySelector("#grilla");
            if (contador) contador.textContent = Number(contador.textContent) - 1;
            if (grilla && grilla.children.length === 0) {
                grilla.innerHTML = '<p class="vacio">No se encontraron productos.</p>';
            }
        }
        cerrarModal();
        mostrarAviso(resultado.mensaje);
    } catch (error) {
        cerrarModal();
        mostrarAviso(`No se pudo eliminar: ${error.message}`, "error");
    } finally {
        boton.disabled = false;
    }
}

function mostrarErroresAlta(lista) {
    const caja = document.querySelector("#erroresAlta");
    if (!caja) return;
    caja.innerHTML = `<p>Corrija lo siguiente:</p>
        <ul class="lista-errores">${lista.map((m) => `<li>${escapar(m)}</li>`).join("")}</ul>`;
    caja.classList.remove("oculto");
}

function validarAlta(form) {
    const nombre = form.elements.nombre;
    const categoria = form.elements.categoria_id;
    const precio = form.elements.precio;
    const errores = [];

    const nombreMal = nombre.value.trim().length < 3;
    const categoriaMal = categoria.value === "";
    const precioMal = !(Number(precio.value) > 0);

    nombre.classList.toggle("invalido", nombreMal);
    categoria.classList.toggle("invalido", categoriaMal);
    precio.classList.toggle("invalido", precioMal);

    if (nombreMal) errores.push("El nombre debe tener al menos 3 caracteres.");
    if (categoriaMal) errores.push("Seleccione una categoría.");
    if (precioMal) errores.push("El precio debe ser mayor a cero.");

    return errores;
}

const vistas = {
    inicio: () => cargarVista("inicio.html"),
    menu: iniciarMenu,
    nuevo: iniciarAlta
};

botonesMenu.forEach((boton) => {
    boton.addEventListener("click", () => {
        clearTimeout(temporizador);
        botonesMenu.forEach((b) => b.classList.remove("activo"));
        boton.classList.add("activo");
        vistas[boton.dataset.vista]();
    });
});

contenido.addEventListener("click", (e) => {
    if (e.target.matches(".btn-detalle")) {
        verDetalle(e.target.dataset.id);
    } else if (e.target.matches(".btn-eliminar")) {
        pedirConfirmacion(e.target.dataset.id, e.target.dataset.nombre);
    }
});

contenido.addEventListener("submit", async (e) => {
    if (!e.target.matches("#formProducto")) return;
    e.preventDefault();

    const form = e.target;
    const errores = validarAlta(form);
    const caja = document.querySelector("#erroresAlta");

    if (errores.length > 0) {
        mostrarErroresAlta(errores);
        return;
    }
    caja.classList.add("oculto");

    const boton = form.querySelector('button[type="submit"]');
    const textoOriginal = boton.textContent;
    boton.disabled = true;
    boton.textContent = "Guardando...";

    try {
        const resp = await fetch("insertar.php", { method: "POST", body: new FormData(form) });
        const resultado = await resp.json();

        if (resp.status === 422) {
            mostrarErroresAlta(resultado.errores);
            return;
        }
        if (!resp.ok) throw new Error(resultado.mensaje || `HTTP ${resp.status}`);

        form.reset();
        Array.from(form.elements).forEach((campo) => campo.classList.remove("invalido"));
        mostrarAviso(resultado.mensaje);
    } catch (error) {
        mostrarAviso(`No se pudo guardar: ${error.message}`, "error");
    } finally {
        boton.disabled = false;
        boton.textContent = textoOriginal;
    }
});

modal.addEventListener("click", (e) => {
    if (e.target === modal || e.target.matches('[data-accion="cerrar"]')) {
        cerrarModal();
    } else if (e.target.matches('[data-accion="eliminar"]')) {
        eliminarProducto(e.target.dataset.id, e.target);
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        cerrarModal();
    }
});

document.querySelector('nav button[data-vista="inicio"]').click();
