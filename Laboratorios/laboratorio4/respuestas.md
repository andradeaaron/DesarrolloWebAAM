# Laboratorio 4 — SIS 256: Respuestas de análisis

**Integrantes:**
- Integrante 1: Crismar Salazar Ramos
- Integrante 2: Andrade Mallco Aarón

---

## 1. ¿Qué diferencia hay entre textContent e innerHTML? ¿Por qué en el Ejercicio 2 los datos del servidor pasan por escapar() y qué ataque se evita?

La diferencia es que textContent sirve para colocar texto normal y lo va a mostrar tal cual, sin interpretar las etiquetas HTML. En cambio, innerHTML permite insertar contenido HTML y el navegador interpreta las etiquetas.

En el Ejercicio 2 utilizamos escapar() porque los datos que mostramos vienen de la base de datos y pueden haber sido escritos por cualquier usuario. Por ejemplo, alguien podría registrar un producto con código HTML o JavaScript malicioso. Si lo insertamos directamente con innerHTML, el navegador podría ejecutar ese códigoy esto puede provocar un ataque llamado **XSS (Cross-Site Scripting)**, que puede afectar la seguridad de los usuarios. Por eso, escapar() reemplaza caracteres especiales como <, > y & por sus entidades HTML, evitando que el navegador los interprete como código.

## 2. Explique la delegación de eventos. ¿Qué ocurriría con los botones Agregar si el listener se registrara en cada botón y luego el usuario filtrara el catálogo?

La delegación de eventos aprovecha el DOM para registrar un único listener en el contenedor del #catálogo en lugar de hacerlo en cada botón, detectando luego si el clic vino de un botón Agregar. Si el listener se registrara en cada botón por separado, al filtrar el catálogo se borran las tarjetas anteriores y se crean botones nuevos en el DOM que no tendrían el listener asignado y dejarian de responder.

## 3. fetch() no rechaza la promesa con un 404 o un 500. ¿Cómo lo detecta su código? ¿En qué casos sí se ejecuta el catch?

Esto sucede porque cuando utilizamos fetch(), un error 404 o 500 no significa que la petición haya fallado a nivel de conexión, sino que el servidor recibió la petición y devolvió una respuesta, aunque tenga un error.
Para detectar estos casos, nuestro código comprueba la propiedad resp.ok. Si devuelve false, significa que la respuesta no fue exitosa y podemos lanzar un error manualmente
El catch se ejecuta cuando ocurre algún problema durante la petición o dentro del bloque tryentoce fetch() no detecta automáticamente los errores HTTP como excepciones, por eso nosotros debemos comprobar la respuesta.

## 4. Escriba la función cargarVista(url) con XMLHttpRequest, como en la clase del 21 de octubre, y compárela con la versión de fetch + async/await.

js
function cargarVista(url) {
        avisos.textContent = "";
        contenido.innerHTML = '<p class="cargando">Cargando...</p>';

        const xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                contenido.innerHTML = xhr.responseText;
            } else {
                contenido.innerHTML = <div class="aviso aviso-error">HTTP ${xhr.status}</div>;
            }
        };
        xhr.onerror = function () {
            contenido.innerHTML = '<div class="aviso aviso-error">Error de red</div>';
        };
        xhr.send();
    }


## 5. ¿Por qué no se debe fijar Content-Type al enviar un FormData con un archivo? ¿Qué pasa en $_POST y $_FILES si se pone "application/json"?

Porque el propio navegador se encarga de generar la cabecera correcta
Esto es importante porque separa los diferentes campos y archivos que estamos enviando y si nosotros modificamos manualmente esa cabecera, podemos provocar que PHP no pueda reconocer correctamente la información recibida.
Si colocamos Content-Type en application/json, PHP no va a interpretar el contenido como un formulario normal. Por lo tanto, las variables $_POST y $_FILES quedarían vacías ya que PHP espera otro formato para recibir esos datos.

## 6. Si JavaScript ya valida el formulario, ¿por qué insertar.php vuelve a validar? Relacione la respuesta con la prueba 7.
Aunque JavaScript nos permite validar los datos antes de enviarlos no se confiar únicamente en esa validación porque el usuario puede modificar el contenido de la página o incluso desactivar JavaScript alguien podría utilizar las herramientas del navegador, la consola o programas como Postman para enviar información directamente al servidor, sin pasar por las validaciones que tenemos en `app.js`por eso también debemos validar los datos en insertar.php, ya que el servidor es el encargado de comprobar que la información
Esto se demuestra en la prueba 7, donde enviamos un formulario vacío directamente desde la consola utilizando `fetch()`. De esta manera evitamos la validación de JavaScript

## 7. Compare localStorage, sessionStorage y la cookie del Laboratorio 2: duración, quién la puede leer (cliente o servidor) y para qué conviene cada una.

localStorage persiste de forma indefinida hasta que se borre, solo puede leerla el cliente con JavaScript y conviene para guardar datos que deben sobrevivir al cierre del navegador como un carrito o preferencias. sessionStorage dura únicamente mientras la pestaña siga abierta, también es solo para el cliente y conviene para estados temporales de un flujo corto. En cambio, la cookie dura hasta su fecha de expiración o al cerrar el navegador y conviene para datos que el servidor necesita identificar en cada consulta, como sesiones o recordar a un usuario.

## 8. ¿Qué es el debounce? ¿Cuántas peticiones se enviarían al escribir "pollo" sin él, y por qué importa en una aplicación con muchos usuarios?

El debounce es una técnica que retrasa la ejecución de una función hasta que el usuario deja de realizar la acción  durante un breve tiempo, reiniciando el temporizador en cada pulsación.
Sin esto, al escribir pollo se enviarían 5 peticiones al servidor, mientras que con debounce solo se envía una al terminar de escribir. Esto evita saturar el servidor y la base de datos con consultas innecesarias.