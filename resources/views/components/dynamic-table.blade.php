<div class="relative overflow-x-auto sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase text-gray-100 dark:text-neutral-900 bg-neutral-900 dark:bg-gray-100">
        <tr>
            @foreach ($headers as $header)
                <th scope="col" class="px-6 py-3">{{ $header }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        {{ $slot }} <!-- L'endroit où les lignes du tableau seront insérées -->
        </tbody>
    </table>
</div>
