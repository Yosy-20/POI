const usuarios = [];
const usuarioInput = document.getElementById("usuarioInput");
const usuariosLista = document.getElementById("usuariosLista");
const usuariosEquipoInput = document.getElementById("usuariosEquipoInput");

usuarioInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        const valor = usuarioInput.value.trim();
        if (valor !== "" && !usuarios.includes(valor)) {
            usuarios.push(valor);
            actualizarLista();
            usuarioInput.value = "";
        }
    }
});

function actualizarLista() {
    usuariosLista.innerHTML = "";
    usuarios.forEach(usuario => {
        const div = document.createElement("div");
        div.classList.add("badge", "bg-primary", "me-1");
        div.textContent = usuario;
        usuariosLista.appendChild(div);
    });
    usuariosEquipoInput.value = JSON.stringify(usuarios);
}
