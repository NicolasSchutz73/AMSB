<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emploi du temps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Menu déroulant des catégories -->
            <select id="category-filter">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <br><br>

            <!-- Calendrier -->
            <div id='calendar'></div>
        </div>
    </div>

    <!-- Script pour filtrer les événements par catégorie -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Votre configuration de calendrier ici
            });
            calendar.render();

            // Filtrer les événements par catégorie lorsqu'une catégorie est sélectionnée dans le menu déroulant
            document.getElementById('category-filter').addEventListener('change', function() {
                var category = this.value;
                if (category) {
                    calendar.getEvents().forEach(function(event) {
                        if (event.extendedProps.categories.includes(category)) {
                            event.setProp('display', 'block');
                        } else {
                            event.setProp('display', 'none');
                        }
                    });
                } else {
                    calendar.getEvents().forEach(function(event) {
                        event.setProp('display', 'block');
                    });
                }
            });
        });
    </script>
</x-app-layout>
