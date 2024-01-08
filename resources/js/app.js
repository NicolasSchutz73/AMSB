import './bootstrap';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import { createApp } from 'vue';

import store from './store';
import GroupChat from './components/GroupChat.vue';
import CreateGroup from './components/CreateGroup.vue';
import Groups from './components/Groups.vue';



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

const app = createApp({
    components: {
        'groups': Groups,
        'group-chat': GroupChat,
        'create-group': CreateGroup
    }
});

app.use(store);
app.mount('#app');
