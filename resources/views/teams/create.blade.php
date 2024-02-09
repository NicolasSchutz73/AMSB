<form action="{{ route('teams.store') }}" method="post">
    @csrf

    <label for="name">Nom d'équipe :</label>
    <input type="text" name="name" value="{{ old('name') }}">

    <label for="category">Catégorie :</label>
    <input type="text" name="category" value="{{ old('category') }}">

    <input type="submit" value="Créer l'équipe">
</form>
