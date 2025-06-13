<?php
require('../Controllers/conexion.php');

// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  session_regenerate_id(true); // Protege contra fijación de sesión
}

// Validar si el usuario está autenticado
if (empty($_SESSION['username'])) {
  header("Location: login.php");
  exit;
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
    <link rel="stylesheet" href="css/stylesChat.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Workly</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="#">Actividad</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-users"></i> Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="chat.php"><i class="fas fa-comments"></i> Chats</a></li>
            <li class="nav-item"><a class="nav-link" href="calendar.html"><i class="fas fa-calendar"></i> Calendario</a></li>
            <li class="nav-item"><a class="nav-link" href="Reward.hphp"><i class="fas fa-gift"></i> Recompensas</a></li>
        </ul>
        <div class="sidebar-footer">Sesión Iniciada: <br> <strong><i class="fas fa-user fa-fw"></i> Usuario</strong> <a href="Perfil.html"> <i class="fas fa-gear" ></i></a></div>
    </div>
    <div class="content">
        <div class="Chats">
            <!-- Sub-sidebar: Lista de Chats -->
            <div class="sub-sidebar">
                <h3>Chats</h3>
                <div class="chatList" id="chatList">
                    
                </div>
            </div>
        
            <!-- Contenedor del Chat -->
            <div class="chat-container">
                <div class="chat-header"> 
                    <p id="nombreChat">Usuario 1</p>

                </div>
               
                <div class="chat-body">
                
                
                </div>
                <div class="chat-footer">
                <input type="text" id="mensajeInput" placeholder="Escribe un mensaje...">
                <button id="enviarBtn"><i class="fas fa-paper-plane"></i></button>
                </div>
                </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Cargar la lista de chats del usuario actual
  document.addEventListener('DOMContentLoaded', () => {
  const chatList = document.getElementById('chatList');

  fetch('../Controllers/obtenerChat.php')
    .then(response => {
      if (!response.ok) {
        throw new Error("No se pudo cargar obtenerChat.php");
      }
      return response.text();
    })
    .then(text => {
      try {
        const data = JSON.parse(text);
        chatList.innerHTML = '';

        if (data.error) {
          console.error('Error recibido del servidor:', data.error);
          chatList.innerHTML = `<div class="chat-item">Error: ${data.error}</div>`;
          return;
        }

        if (data.length === 0) {
          chatList.innerHTML = '<div class="chat-item">Sin chats disponibles</div>';
        } else {
          data.forEach(chat => {
            const item = document.createElement('div');
            item.className = 'chat-item';
            item.innerHTML = `<img src="${chat.foto}" alt="Usuario"> ${chat.nombre}`;
            item.onclick = () => {
              document.getElementById('nombreChat').textContent = chat.nombre;
              sessionStorage.setItem("selectedUser", chat.nombre);
              window.location.href = "chat.php";
            };
            chatList.appendChild(item);
          });
        }
      } catch (err) {
        console.error('Respuesta no es JSON válido:', text);
        console.error('Error al parsear JSON:', err);
      }
    })
    .catch(error => {
      console.error('Error al cargar chats:', error);
    });
});

  </script>
   <script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getDatabase, ref, push, onChildAdded, get, child } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";
import CryptoJS from "https://cdn.jsdelivr.net/npm/crypto-js@4.1.1/+esm";

// ⚙️ Configuración Firebase
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

// 🔥 Inicializar Firebase
const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// 🔑 Clave secreta para cifrado AES
const secretKey = "worklySecret123";

// 🧑‍🤝‍🧑 Usuarios
const currentUser = "<?php echo $_SESSION['username']; ?>"; // Desde PHP
const selectedUser = sessionStorage.getItem("selectedUser"); // Desde lista de chats
const userPhoto = "<?php echo $fotoDestino?>";

// 🧩 ID único del chat
const chatId = [currentUser, selectedUser].sort().join("_");
const messagesRef = ref(db, `chats/${chatId}/mensajes`);

// 🖥️ Referencias al DOM
const chatBody = document.querySelector(".chat-body");
const input = document.querySelector("#mensajeInput");
const button = document.querySelector("#enviarBtn");

// Mostrar nombre en encabezado
document.querySelector(".chat-header p").textContent = selectedUser;

// 🌐 Verificar si hay mensajes
get(child(ref(db), `chats/${chatId}/mensajes`)).then(snapshot => {
    if (!snapshot.exists()) {
        const div = document.createElement("div");
        div.className = "message received";
        div.innerHTML = `<p><em>No hay mensajes aún</em></p>`;
        chatBody.appendChild(div);
    }
});

// 📥 Escuchar mensajes nuevos (con descifrado)
onChildAdded(messagesRef, (data) => {
    const msg = data.val();
    const isCurrentUser = msg.name === currentUser;

    let decryptedText = "";
    try {
        const bytes = CryptoJS.AES.decrypt(msg.text, secretKey);
        decryptedText = bytes.toString(CryptoJS.enc.Utf8);
    } catch (e) {
        console.error("Error al descifrar mensaje:", e);
        decryptedText = "(Mensaje ilegible)";
    }

    const div = document.createElement("div");
    div.className = "message " + (isCurrentUser ? "sent" : "received");

    const img = msg.photoURL
        ? `<img src="${msg.photoURL}" class="user-photo" alt="foto de ${msg.name}">`
        : `<i class="default-icon"></i>`;

    div.innerHTML = `
        <div class="message-content">
            ${img}<strong>${msg.name}</strong> <p>${decryptedText}</p>
        </div>`;

    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
});

// 📤 Enviar mensaje (con cifrado)
button.addEventListener("click", () => {
    const text = input.value.trim();
    if (text !== "") {
        const encryptedText = CryptoJS.AES.encrypt(text, secretKey).toString();

        push(messagesRef, {
            name: currentUser,
            text: encryptedText,
            photoURL: userPhoto,
            timestamp: Date.now()
        });

        input.value = "";
    }
});

// Enviar con Enter
input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") button.click();
});
</script>

</body>
</html>
