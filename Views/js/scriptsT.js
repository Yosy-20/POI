const tasks = {
    proximamente: [
        { title: "Análisis de aproximaciones de neuromarketing", info: "Vence el 10:00 | 053 E2025 Mercadotecnia | CLASE ORDINARIA", points: "8 puntos" }
    ],
    vencida: [
        { title: "Entrega de reporte de ventas", info: "Venció el 15 feb | 053 E2025 Mercadotecnia", points: "10 puntos" }
    ],
    completada: [
        { title: "Exposición de caso de estudio", info: "Completado el 10 feb | 053 E2025 Mercadotecnia", points: "5 puntos" }
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