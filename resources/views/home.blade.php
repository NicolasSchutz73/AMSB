<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Title Here') }}
        </h2>
    </x-slot>

    <!-- This is where the Vue.js app will attach -->
    <div id="app">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Vue component -->
                            <create-group :initial-users="{{ $users }}"></create-group>
                        </div>
                        <div class="col-sm-6">
                            <!-- Vue component -->
                            <groups :initial-groups="{{ $groups }}" :user="{{ $user }}"></groups>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End of Vue.js app div -->
</x-app-layout>
