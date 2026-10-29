var recetaActual;   // id de la receta que esta abierta en el modal

function pregunta4()
{
    var contenedor;
	contenedor = document.getElementById('contenido');
	fetch('catalogo.php')
		.then(response => response.text())
		.then(data => contenedor.innerHTML=data);

}
function abrirmodal(id){
   recetaActual = id;   // guardamos el id para usarlo al eliminar
   contenedor = document.getElementById('contenido-modal');
   fetch('datos-receta.php?id='+id)
		.then(response => response.text())
		.then(data => contenedor.innerHTML=data); 

   document.querySelector(".modal").style.display="block";
}
function cerrarmodal(){
    document.querySelector(".modal").style.display="none";
}
function eliminarReceta(){
    if (!confirm("¿Eliminar esta receta?")) return;   // pedir confirmacion

    fetch('eliminar.php?id='+recetaActual, { method: "DELETE" })
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                cerrarmodal();   // cerrar el modal
                pregunta4();     // recargar el catalogo con otro fetch
            } else {
                alert("No se pudo eliminar: " + data.error);
            }
        })
        .catch(error => alert("Error: " + error.message));
}
