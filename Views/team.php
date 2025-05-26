<?php
require('../Controllers/conexion.php');
include("../Controllers/equipoController.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  session_regenerate_id(true);
}

if (empty($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
    die("No se especificó el equipo.");
}

$idEquipo = intval($_GET['id']);
$equipoController = new equipoController($conexion);
$equipo = $equipoController->obtenerEquipoPorId($idEquipo);

if (!$equipo) {
    die("Equipo no encontrado.");
}

// Iniciales para avatar
function obtenerIniciales($nombre) {
    $palabras = explode(' ', $nombre);
    $iniciales = '';
    foreach ($palabras as $palabra) {
        $iniciales .= strtoupper($palabra[0]);
    }
    return $iniciales;
}

$idChat = $equipoController->obtenerIdChatPorEquipo($idEquipo);
if (!$idChat) {
    die("Chat no encontrado para este equipo.");
}


$fotoDestino = $_SESSION['foto'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/styleTeam.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Workly</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Configuración</a></li>
                    <li><a class="dropdown-item" href="#">Actividad</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Controllers/cerrarSesion.php">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a></li>
         <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
        <li class="nav-item"><a class="nav-link" href="task.html"><i class="fas fa-tasks"></i> Tareas</a></li>
        <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
        <li class="nav-item"><a class="nav-link" href="Reward.html"><i class="fas fa-gift"></i> Recompensas</a></li>
    </ul>
    <div class="sidebar-footer">
        Sesión Iniciada: <br>
        <strong><i class="fas fa-user fa-fw"></i> <?= $_SESSION['username'] ?></strong>
        <a href="Perfil.html"><i class="fas fa-gear"></i></a>
    </div>
</div>

<div class="content">
    <div class="Chats">
        <div class="sub-sidebar">
            <div class="profile">
                <div class="profile-icon" style="background-color: <?= htmlspecialchars($equipo['color']) ?>">
                    <?= obtenerIniciales($equipo['nombre']) ?>
                </div>
                <span class="team-name"><?= htmlspecialchars($equipo['nombre']) ?></span>
                <i class="fas fa-ellipsis-v more-options"></i>
            </div>
            <ul class="menu">
                <a href="teamT.php?id=<?= $equipo['id'] ?>"><li><i class="fas fa-tasks"></i> Tareas</li></a>
            </ul>
            <hr>
            <div class="channels">
                <span><i class="fa-solid fa-thumbtack"></i> Canales de texto</span>
                <ul>
                    <a href="team.php?id=<?= $equipo['id'] ?>"><li><i class="fas fa-comments"></i> General</li></a>
                </ul>
                <span><i class="fa-solid fa-thumbtack"></i> Canales de voz</span>
                <ul>
                    <a href="teamV.php?id=<?= $equipo['id'] ?>"><li><i class="fa-solid fa-bullhorn"></i> General</li></a>
                </ul>
            </div>
        </div>

        <div class="chat-container">
            <div class="channel-header" style="background-color: <?= htmlspecialchars($equipo['color']) ?>">
                <h3><i class="fas fa-comments"></i> General</h3>
                <div class="icons">
                    <i class="fas fa-thumbtack"></i>
                    <i class="fas fa-bell"></i>

                </div>
            </div>
            <div class="chat-messages"></div>
            <div class="message-input">
                <input type="file" id="fileInput" style="display: none;">
                <div id="filePreview" style="margin-bottom: 10px;"></div>
                <input type="text" id="mensajeInput" placeholder="Mensaje">
                
                <div class="input-icons">
                    <i class="fas fa-image"></i>
                    <i class="fa-solid fa-file"></i>
                
                </div>
                <button id="enviarBtn" style="background-color: <?= htmlspecialchars($equipo['color']) ?>"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<style>

.message.sent .message-content{
background-color:<?= htmlspecialchars($equipo['color']) ?>;

}
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getDatabase, ref, push, onChildAdded, get } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";
import { createClient } from "https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm";

// Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyAj0KIOZnxsmfx9b_tK3908I9G-xGdrPoo",
    authDomain: "workly-c779e.firebaseapp.com",
    databaseURL: "https://workly-c779e-default-rtdb.firebaseio.com",
    projectId: "workly-c779e",
    storageBucket: "workly-c779e.appspot.com",
    messagingSenderId: "313015636747",
    appId: "1:313015636747:web:0304548dcf15b16488080e",
    measurementId: "G-HLM9N6Z1CG"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// Supabase config
const supabaseUrl = 'https://joqcihawsuqexrwtddah.supabase.co';
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImpvcWNpaGF3c3VxZXhyd3RkZGFoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgyNDE4MTIsImV4cCI6MjA2MzgxNzgxMn0.fRFNj038KEwok08D5eg6sLN6POPtXwhg4df3neG4eOU'; // ← aquí recorta en producción
const supabase = createClient(supabaseUrl, supabaseAnonKey);

const currentUser = "<?php echo $_SESSION['username']; ?>";
const userPhoto = "<?php echo $fotoDestino?>";
const idChat = <?= $idChat ?>;

const messagesRef = ref(db, `chats/${idChat}/mensajes`);
const chatBody = document.querySelector(".chat-messages");
const input = document.querySelector("#mensajeInput");
const button = document.querySelector("#enviarBtn");
const fileInput = document.querySelector("#fileInput");
const fileIcon = document.querySelector(".fa-file");
const filePreview = document.querySelector("#filePreview");

let selectedFile = null;

// Mostrar mensajes iniciales
get(messagesRef).then(snapshot => {
    if (!snapshot.exists()) {
        const div = document.createElement("div");
        div.className = "message";
        div.innerHTML = `<p><em>No hay mensajes aún</em></p>`;
        chatBody.appendChild(div);
    }
});

// Escuchar nuevos mensajes
// Escuchar nuevos mensajes (con descifrado de texto)
onChildAdded(messagesRef, (data) => {
    const msg = data.val();
    const isCurrentUser = msg.name === currentUser;

    const div = document.createElement("div");
    div.className = "message " + (isCurrentUser ? "sent" : "received");

    const img = msg.photoURL
        ? `<img src="${msg.photoURL}" class="avatar" alt="foto de ${msg.name}">`
        : `<img src="../uploads/default.jpg" class="avatar" alt="default">`;

    let content = `<strong>${msg.name}</strong><br>`;

    if (msg.text && msg.text.trim() !== "") {
        let decryptedText = "";
        if (msg.text !== "(Archivo adjunto)") {
            try {
                const bytes = CryptoJS.AES.decrypt(msg.text, secretKey);
                decryptedText = bytes.toString(CryptoJS.enc.Utf8);
            } catch (e) {
                console.error("Error al descifrar mensaje:", e);
                decryptedText = "(Mensaje ilegible)";
            }
        } else {
            decryptedText = msg.text;
        }
        content += `<p>${decryptedText}</p>`;
    }

    if (msg.fileURL) {
        const isImage = msg.fileName && msg.fileName.match(/\.(jpeg|jpg|gif|png|webp)$/i);
        if (isImage) {
            content += `<div class="file-preview">
                            <img src="${msg.fileURL}" alt="${msg.fileName}" style="max-width:200px; border-radius:5px; margin-top:5px;">
                        </div>`;
        } else {
            content += `<div class="file-link">
                            <a href="${msg.fileURL}" target="_blank">📎 ${msg.fileName}</a>
                        </div>`;
        }
    }

    div.innerHTML = `${img}<div class="message-content">${content}</div>`;
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
});
// Clic en ícono de archivo
fileIcon.addEventListener("click", () => {
    fileInput.click();
});

// Selección de archivo
fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const allowedTypes = ["image/", "application/pdf", "application/msword"];
    if (!allowedTypes.some(type => file.type.startsWith(type))) {
        alert("Este tipo de archivo no está permitido.");
        fileInput.value = "";
        return;
    }

    selectedFile = file;

    const preview = document.createElement("div");
    preview.style.padding = "5px";
    preview.style.border = "1px solid #ccc";
    preview.style.borderRadius = "5px";
    preview.style.display = "inline-block";
    preview.style.marginTop = "5px";

    if (file.type.startsWith("image/")) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = "150px";
        img.style.maxHeight = "100px";
        img.style.display = "block";
        preview.appendChild(img);
    } else {
        preview.innerHTML = `📎 <strong>${file.name}</strong>`;
    }

    filePreview.innerHTML = "";
    filePreview.appendChild(preview);
});

// Enviar mensaje
//  Clave secreta para cifrado AES
const secretKey = "worklySecret123";

// Enviar mensaje (con cifrado de texto)
button.addEventListener("click", async () => {
    const text = input.value.trim();
    if (text === "" && !selectedFile) return;

    let fileURL = null;
    let fileName = null;

    button.disabled = true;
    const originalButtonHTML = button.innerHTML;
    button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Subiendo...`;

    if (selectedFile) {
        const cleanFileName = selectedFile.name.replace(/[^a-zA-Z0-9.\-_]/g, "_");
        const filePath = `chats/${idChat}/${Date.now()}_${cleanFileName}`;

        try {
            const { data, error } = await supabase
                .storage
                .from('workly')
                .upload(filePath, selectedFile, { upsert: false });

            if (error) throw error;

            const { data: publicUrlData, error: publicUrlError } = supabase
                .storage
                .from('workly')
                .getPublicUrl(filePath);

            if (publicUrlError) throw publicUrlError;

            fileURL = publicUrlData.publicUrl;
            fileName = selectedFile.name;

            console.log("Archivo subido a Supabase:", fileURL);
        } catch (err) {
            console.error("Error al subir archivo a Supabase:", err);
            alert("Hubo un error al subir el archivo.");
            button.disabled = false;
            button.innerHTML = originalButtonHTML;
            return;
        }
    }

    try {
        // 🔒 Cifrar texto si existe
        const encryptedText = text
            ? CryptoJS.AES.encrypt(text, secretKey).toString()
            : (fileURL ? "(Archivo adjunto)" : "");

        await push(messagesRef, {
            name: currentUser,
            text: encryptedText,
            fileURL: fileURL,
            fileName: fileName,
            photoURL: userPhoto,
            timestamp: Date.now()
        });
        console.log("Mensaje guardado en Realtime Database");
    } catch (error) {
        console.error("Error al guardar mensaje en DB:", error);
        alert("Hubo un error al guardar el mensaje.");
    }

    input.value = "";
    selectedFile = null;
    fileInput.value = "";
    filePreview.innerHTML = "";

    button.disabled = false;
    button.innerHTML = originalButtonHTML;
});

// Enviar con Enter
input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") button.click();
});
</script>




</body>
</html>
