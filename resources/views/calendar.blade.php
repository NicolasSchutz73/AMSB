<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Emploi du temps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Menu déroulant des catégories -->
            <select id="category-filter" onchange="changeCategory()">
                <option value="">Toutes les catégories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <script>
                function changeCategory() {
                    var category = document.getElementById('category-filter').value;
                    window.location.href = '/calendar?category=' + encodeURIComponent(category);
                }
            </script>

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

            // Récupérer la catégorie depuis l'URL
            var urlParams = new URLSearchParams(window.location.search);
            var category = urlParams.get('category');
            console.log('Category from URL:', category); // Vérifiez que la catégorie est correctement extraite de l'URL

            // Filtrer les événements par catégorie lorsqu'une catégorie est spécifiée dans l'URL
            if (category) {
                calendar.getEvents().forEach(function(event) {
                    console.log('Event categories:', event.extendedProps.categories); // Vérifiez les catégories de chaque événement
                    if (event.extendedProps.categories.includes(category)) {
                        event.setProp('display', 'block');
                    } else {
                        event.setProp('display', 'none');
                    }
                });
            } else {
                // Afficher tous les événements si aucune catégorie n'est spécifiée dans l'URL
                calendar.getEvents().forEach(function(event) {
                    event.setProp('display', 'block');
                });
            }

            // Filtrer les événements par catégorie lorsqu'une catégorie est sélectionnée dans le menu déroulant
            document.getElementById('category-filter').addEventListener('change', function() {
                var category = this.value;
                console.log('Selected category:', category); // Vérifiez la catégorie sélectionnée dans le menu déroulant
                if (category) {
                    calendar.getEvents().forEach(function(event) {
                        console.log('Event categories:', event.extendedProps.categories); // Vérifiez les catégories de chaque événement
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
