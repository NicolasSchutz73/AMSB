import './bootstrap';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async function() {
    const calendarEl = document.querySelector('#calendar');

    if (calendarEl == null) return;

    //const { data } = await axios.get('/api/events');

    const events = [
        {
            "id": 1,
            "title": "test",
            "color": "blue",
            "start": "2024-01-06 10:00:00",
            "end": "2024-01-06 11:00:00",
            "borderColor": "green",
        },
        {
            "id": 2,
            "title": "test2",
            "color": "orange",
            "start": "2024-01-07 16:00:00",
            "end": "2024-01-07 18:00:00",
            "borderColor": "purple",
        },
        {
            "id": 3,
            "title": "test3",
            "color": "yellow",
            "start": "2024-01-06 12:00:00",
            "end": "2024-01-06 14:00:00",
            "borderColor": "red",
        }
    ];

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
        events: events,
    });

    calendar.setOption('locale', 'fr');
    calendar.render();
});
