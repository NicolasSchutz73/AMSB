<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div id="app" class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Chat Room</h1>

    <!-- Création de groupe -->
    <div class="mb-4">
        <input type="text" id="groupName" placeholder="Nom du groupe" class="border p-2 rounded mr-2">
        <button id="createGroupButton" class="bg-blue-500 text-white p-2 rounded">Créer Groupe</button>
    </div>

    <!-- Liste des utilisateurs pour la création de groupe -->
    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-2">Utilisateurs</h2>
        <div id="userList" class="bg-white p-4 rounded shadow">
            <!-- Les utilisateurs seront chargés ici -->
        </div>
    </div>

    <!-- Liste des groupes -->
    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-2">Mes Groupes</h2>
        <div id="groupList" class="bg-white p-4 rounded shadow">
            <!-- Les groupes seront chargés ici -->
        </div>
    </div>

    <!-- Zone de chat -->
    <div class="chat-container bg-white p-4 rounded shadow">
        <div id="groupChatHeader" class="text-lg font-bold text-blue-600 mb-2">
            <!-- Le nom du groupe sera affiché ici -->
        </div>
        <div id="chat" class="chat-messages overflow-y-auto h-64 mb-4 space-y-2">
            <!-- Les messages seront affichés ici -->
        </div>
            <!-- Les messages seront affichés ici -->
        </div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="Tapez votre message ici..." class="border p-2 rounded w-full">
            <button id="submitButton" class="bg-green-500 text-white p-2 rounded mt-2">Envoyer</button>
        </div>
    </div>

</div>

@vite('resources/js/chatRoom/app.js')
@vite('resources/js/bootstrap.js')
</body>
</html>
