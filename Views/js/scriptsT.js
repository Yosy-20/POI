const tasks = {
    proximamente: [
        { title: "Tarea 3", info: "Vence el 10:00 | Equipo 1 ", points: "8 puntos" }
    ],
    vencida: [
        { title: "Tarea 2", info: "Venció el 15 feb | Equipo 1", points: "10 puntos" }
    ],
    completada: [
        { title: "Tarea 1", info: "Completado el 10 feb |Equipo 1", points: "5 puntos" }
    ]
};

function changeTab(category) {
    document.querySelectorAll(".tab").forEach(tab => tab.classList.remove("active"));
    document.querySelector(`.tab[onclick="changeTab('${category}')"]`).classList.add("active");
    
    const container = document.getElementById("task-container");
    container.innerHTML = "";

    tasks[category].forEach(task => {
        const taskElement = document.createElement("div");
        taskElement.classList.add("task");
        taskElement.innerHTML = `
            <div class="task-title">${task.title}</div>
            <div class="task-info">${task.info}</div>
        `;
        container.appendChild(taskElement);
    });
}

// Cargar la pestaña por defecto
changeTab('proximamente');