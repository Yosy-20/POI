document.addEventListener("DOMContentLoaded", function () {
    const usuarioInput = document.getElementById("usuarioInput");
    const usuariosLista = document.getElementById("usuariosLista");
    let usuarios = [];

    usuarioInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            let nombreUsuario = usuarioInput.value.trim();
            if (nombreUsuario && !usuarios.includes(nombreUsuario)) {
                usuarios.push(nombreUsuario);
                let userTag = document.createElement("span");
                userTag.className = "badge bg-secondary me-2 p-2";
                userTag.innerHTML = `${nombreUsuario} <i class="fas fa-times ms-1 remove-user" style="cursor:pointer;"></i>`;
                usuariosLista.appendChild(userTag);
                usuarioInput.value = "";

                userTag.querySelector(".remove-user").addEventListener("click", function () {
                    usuariosLista.removeChild(userTag);
                    usuarios = usuarios.filter(user => user !== nombreUsuario);
                });
            }
        }
    });
});
