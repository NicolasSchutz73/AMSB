import './bootstrap';
import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async function() {
    const calendarEl = document.querySelector('#calendar');

    if (calendarEl == null) return;

    const { data } = await axios.get('/api/events');



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

const nicknameInput = document.getElementById("nickname");
const messageInput = document.getElementById("message");
const chatDiv = document.getElementById('chat');
const submitButton = document.getElementById('submitButton');

submitButton.addEventListener('click', () => {
    axios.post('/chat', {
        nickname: nicknameInput.value,
        message: messageInput.value
    }).then(response => {
        console.log(response);

    }).catch(error => {
        console.error(error);
    });
});


window.Echo.channel('chat').listen('.chat-message', (event)=>{
    console.log(event)

    chatDiv.innerHTML += `
   <div class="other break-all mt-2  ml-5 rounded-bl-none float-none bg-gray-300 mr-auto rounded-2xl p-2">
        <p>${event.message} par  <em>${event.nickname}</em></p>



        </div>
`


})

