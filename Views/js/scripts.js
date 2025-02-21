
let currentDate = new Date();
let viewMode = 'month';
const weekdays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
const hours = Array.from({ length: 24 }, (_, i) => `${i}:00`);

function updateCalendar() {
    const calendar = document.getElementById('calendar');
    const currentMonth = document.getElementById('currentMonth');
    calendar.innerHTML = '';
    
    let year = currentDate.getFullYear();
    let month = currentDate.getMonth();
    let firstDay = new Date(year, month, 1).getDay();
    let daysInMonth = new Date(year, month + 1, 0).getDate();
    currentMonth.textContent = currentDate.toLocaleString('es-ES', { month: 'long', year: 'numeric' });
    
    if (viewMode === 'month') {
        calendar.style.display = 'grid';
        calendar.style.gridTemplateColumns = 'repeat(7, 1fr)';
        
        weekdays.forEach(day => {
            let weekdayElement = document.createElement('div');
            weekdayElement.classList.add('weekday');
            weekdayElement.textContent = day;
            calendar.appendChild(weekdayElement);
        });
        
        for (let i = 0; i < firstDay; i++) {
            let emptyDay = document.createElement('div');
            emptyDay.classList.add('day');
            emptyDay.style.visibility = 'hidden';
            calendar.appendChild(emptyDay);
        }
        
        for (let i = 1; i <= daysInMonth; i++) {
            let dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = i;
            calendar.appendChild(dayElement);
        }
    } else if (viewMode === 'week') {
        calendar.classList.add('agenda-view');
        calendar.style.display = 'grid';
        calendar.style.gridTemplateColumns = '100px repeat(7, 1fr)';
        
        let startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
        
        let emptyHeader = document.createElement('div');
        calendar.appendChild(emptyHeader);
        
        weekdays.forEach(day => {
            let weekdayElement = document.createElement('div');
            weekdayElement.classList.add('weekday');
            weekdayElement.textContent = day;
            calendar.appendChild(weekdayElement);
        });
        
        hours.forEach(hour => {
            let hourCell = document.createElement('div');
            hourCell.classList.add('hour-cell');
            hourCell.textContent = hour;
            calendar.appendChild(hourCell);
            
            for (let i = 0; i < 7; i++) {
                let dayCell = document.createElement('div');
                dayCell.classList.add('hour-cell');
                calendar.appendChild(dayCell);
            }
        });
    } else {
        calendar.classList.add('agenda-view');
        calendar.style.display = 'grid';
        calendar.style.gridTemplateColumns = '100px 1fr';
        
        hours.forEach(hour => {
            let hourCell = document.createElement('div');
            hourCell.classList.add('hour-cell');
            hourCell.textContent = hour;
            calendar.appendChild(hourCell);
            
            let eventCell = document.createElement('div');
            eventCell.classList.add('hour-cell');
            calendar.appendChild(eventCell);
        });
    }
}

function prevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
}

function changeView(view) {
    viewMode = view;
    updateCalendar();
}

updateCalendar();