<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emploi du temps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <select id="category-filter" onchange="changeCategory()">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <br><br>

            <div id='calendar'></div>
        </div>
    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.11/index.global.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Encodage direct en JSON sans JSON.parse
            var events = {!! json_encode($events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start' => $event->start,
                'end' => $event->end,
                'isRecurring' => $event->isRecurring,
                // Ajoutez ici d'autres propriétés nécessaires pour FullCalendar
            ];
        })->toArray(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};

            console.log(events);

                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listDay'
            },
                initialView: 'dayGridMonth', // This will show the month view with blocks
                events: events
            });
                calendar.render();
        });

        function changeCategory() {
            var category = document.getElementById('category-filter').value;
            window.location.href = '/calendar?category=' + encodeURIComponent(category);
        }
    </script>
</x-app-layout>
