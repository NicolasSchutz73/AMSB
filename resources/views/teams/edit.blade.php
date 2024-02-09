<h1>Édition de l'équipe</h1>

<form action="{{ route('teams.update', $team->id) }}" method="post">
    @csrf
    @method('PUT')

    <label for="name">Nom de l'équipe :</label>
    <input type="text" name="name" value="{{ $team->name }}">

    <label for="category">Catégorie :</label>
    <input type="text" name="category" value="{{ $team->category }}">

    {{-- Champs pour ajouter des utilisateurs à l'équipe --}}
    <label for="add_users">Ajouter des utilisateurs :</label>
    <select name="add_users[]" multiple>
        @foreach ($allUsers as $user)
            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
        @endforeach
    </select>

    {{-- Champs pour supprimer des utilisateurs de l'équipe --}}
    <label for="remove_users">Supprimer des utilisateurs :</label>
    <select name="remove_users[]" multiple>
        @foreach ($team->users as $user)
            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
        @endforeach
    </select>

    <input type="submit" value="Mettre à jour">
</form>
