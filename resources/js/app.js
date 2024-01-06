import './bootstrap';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import { createApp } from 'vue'; // Importer createApp de Vue 3
import Groups from './components/Groups.vue'; // Assurez-vous que ce chemin est correct

// Démarrer Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Attendre que le DOM soit chargé avant de lancer des opérations DOM
document.addEventListener('DOMContentLoaded', async function() {
    const calendarEl = document.querySelector('#calendar');

    if (calendarEl == null) return;

    const { data } = await axios.get('/api/events');

    // Initialiser FullCalendar
    const calendar = new Calendar(calendarEl, {
        plugins: [ timeGridPlugin ],
        initialView: 'timeGridWeek',
        slotMinTime: "08:00:00",
        slotMaxTime: "23:00:00",
        eventClick: async function(info) {
            let { data } = await axios.put('/api/subscribe', {
                id: info.event.id,
            });

            info.el.style.borderColor = data.attached === true ? 'green' : 'yellow';
        },
        events: data.events,
    });

    calendar.setOption('locale', 'fr');
    calendar.render();
});

// Vérifier si l'élément #app existe avant d'essayer de monter l'application Vue
if (document.querySelector('#app')) {
    // Créer une nouvelle instance de l'application Vue
    createApp({
        components: {
            'groups': Groups
        }
    }).mount('#app');
}
