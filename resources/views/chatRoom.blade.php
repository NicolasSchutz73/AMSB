<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
</head>
<body>
<div id="app">
    <h1>Chat Room</h1>
    <div>
        <input type="text" id="groupName" placeholder="Nom du groupe">
        <button id="createGroupButton">Créer Groupe</button>
    </div>
    <div id="userList">
        <!-- La liste des utilisateurs sera chargée ici -->
    </div>

    <!-- Liste des groupes de l'utilisateur -->
    <h2>Mes Groupes</h2>
    <div id="groupList"></div>
</div>

@vite('resources/js/chatRoom/app.js')
@vite('resources/js/bootstrap.js')

</body>
</html>
