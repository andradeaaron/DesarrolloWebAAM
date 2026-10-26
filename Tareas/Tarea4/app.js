// Tarea4 Carga de contenido con AJAX (Fetch API + async/await)
// Solo se reemplaza el contenido de <main>; header, aside y footer NO se recargan.

document.addEventListener("DOMContentLoaded", () => {
    const enlaces = document.querySelectorAll("aside a.enlace-ajax");
    const mainContainer = document.querySelector("#contenido-principal");

    enlaces.forEach(enlace => {
        enlace.addEventListener("click", async (evento) => {
            evento.preventDefault();                       // evita el salto/recarga del enlace

            const url = enlace.getAttribute("data-url");   // la URL esta en data-url, no en href
            if (!url) return;

            try {
                mainContainer.innerHTML = "<p>Cargando contenido...</p>";

                const respuesta = await fetch(url);
                if (!respuesta.ok) {
                    throw new Error(`Error HTTP: ${respuesta.status}`);
                }

                const htmlTexto = await respuesta.text();

                // Las paginas (miciudad.html, etc.) son documentos completos:
                // tomamos solo lo que esta dentro de su <body>.
                const doc = new DOMParser().parseFromString(htmlTexto, "text/html");
                mainContainer.innerHTML = doc.body.innerHTML;

            } catch (error) {
                console.error("Error al cargar la pagina:", error);
                mainContainer.innerHTML = "<p>Hubo un error al cargar el contenido</p>";
            }
        });
    });
});
