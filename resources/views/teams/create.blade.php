<form action="{{ route('teams.store') }}" method="post">
    @csrf

    <label for="name">Nom d'équipe :</label>
    <input type="text" name="name" value="{{ old('name') }}">

    <label for="category">Catégorie :</label>
    <input type="text" name="category" value="{{ old('category') }}">

    {{-- Champs pour ajouter des utilisateurs à l'équipe --}}
    <label for="add_users">Ajouter des utilisateurs :</label>
    <select name="add_users[]" multiple>
        @foreach ($allUsers as $user)
            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
        @endforeach
    </select>

    <input type="submit" value="Créer l'équipe">
</form>
