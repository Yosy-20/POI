function mostrarSeccion(id) {
    const secciones = document.querySelectorAll('.seccion-tareas');
    secciones.forEach(sec => sec.style.display = 'none');
    document.getElementById(id).style.display = 'block';
}