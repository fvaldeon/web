//Al pulsar el checkbox ejecuto esta funcion

function mostrar() {
    //hace visibles unos elementos y requerido nombre de entrega
    document.getElementById("nombre_entrega").toggleAttribute("hidden");
    document.getElementById("nombre_entrega").toggleAttribute("required");
    document.getElementById("label_tamano_entrega").toggleAttribute("hidden");
    document.getElementById("spinner_tamano_entrega").toggleAttribute("hidden");
};

//Seleccionar/deseleccionar todos los checkboxes
function seleccion(source) {
    checkboxes = document.getElementsByName('seleccionar');
    for (var checkbox of checkboxes) {
        checkbox.checked = source.checked;
    }
};