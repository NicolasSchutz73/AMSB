import axios from "axios";
import Echo from 'laravel-echo';

let currentGroupId = null;
let groupChannels = {};

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn_affiche_user').addEventListener('click', openModal);
    document.getElementById('btn_fermer_modal_').addEventListener('click', closeModal);
    document.getElementById('nouveau-groupe-btn').addEventListener('click', toggleGroupCreationMode);
    document.getElementById('nextButton').addEventListener('click', createGroup);


    const sendButton = document.getElementById('sendButton');
    const messageInput = document.getElementById('messageInput');

    sendButton.addEventListener('click', function() {
        sendMessage(messageInput.value); // Envoie le contenu du champ de saisie
        messageInput.value = ''; // Efface le champ après l'envoi
    });

    loadUserGroups();

});

let isGroupCreationActive = false;




function toggleGroupCreationMode() {
    isGroupCreationActive = !isGroupCreationActive; // Inverse l'état à chaque clic

    const checkboxes = document.querySelectorAll('#userListContainer input[type="checkbox"]');
    const nextButton = document.getElementById('nextButton');
    const groupNameContainer = document.getElementById('groupNameContainer'); // Récupère le conteneur du champ de saisie du nom du groupe

    if (isGroupCreationActive) {
        document.getElementById('groupNameInput').value = ''; // Vide l'input du nom du groupe

        // Affiche les checkboxes, le bouton "Suivant" et le champ de saisie du nom du groupe
        checkboxes.forEach(checkbox => checkbox.classList.remove('hidden'));
        nextButton.classList.remove('hidden');
        groupNameContainer.classList.remove('hidden'); // Affiche le champ de saisie du nom du groupe
    } else {
        // Masque les checkboxes, le bouton "Suivant" et le champ de saisie du nom du groupe
        checkboxes.forEach(checkbox => {
            checkbox.classList.add('hidden');
            checkbox.checked = false; // Décoche toutes les checkboxes
        });
        nextButton.classList.add('hidden');
        groupNameContainer.classList.add('hidden'); // Masque le champ de saisie du nom du groupe
    }
}




function loadUsers() {
    axios.get('/api/users')
        .then(response => {
            const users = response.data;
            const userListContainer = document.getElementById('userListContainer');
            userListContainer.innerHTML = ''; // Nettoie la liste actuelle

            users.forEach(user => {
                const userItem = document.createElement('li');
                userItem.classList.add("flex", "items-center", "justify-between", "mb-2");
                userItem.setAttribute('data-user-id', user.id); // Stocke l'ID de l'utilisateur pour une utilisation ultérieure

                const userInfo = document.createElement('span');
                userInfo.textContent = `${user.firstname} ${user.lastname}`;
                userInfo.classList.add("text-gray-800", "flex-grow");

                const userCheckbox = document.createElement('input');
                userCheckbox.type = 'checkbox';
                userCheckbox.classList.add("form-checkbox", "h-5", "w-5", "text-blue-600", "hidden");
                userCheckbox.setAttribute('data-user-id', user.id);

                userItem.appendChild(userInfo);
                userItem.appendChild(userCheckbox);

                userItem.addEventListener('click', function(event) {
                    if (!isGroupCreationActive) {
                        // Mode normal : ouvrir une conversation
                        startConversation(user.id);
                    } else if (event.target.type !== 'checkbox') {
                        // Mode sélection : cocher/décocher la checkbox si l'utilisateur n'a pas cliqué directement dessus
                        userCheckbox.checked = !userCheckbox.checked;
                    }
                });

                userListContainer.appendChild(userItem);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des utilisateurs', error));
}




function createGroup() {
    const selectedUserIds = Array.from(document.querySelectorAll('#userListContainer input[type="checkbox"]:checked'))
        .map(checkbox => checkbox.getAttribute('data-user-id'));
    const groupName = document.getElementById('groupNameInput').value; // Récupère la valeur du champ de saisie du nom du groupe

    console.log("Créer un groupe nommé:", groupName, "avec les utilisateurs IDs:", selectedUserIds);

    axios.post('/create-group', {
        groupName: groupName,
        userIds: selectedUserIds
    })
        .then(() => {
            console.log('Groupe créé avec succès');
        })
        .catch(error => {
            console.error('Erreur lors de la création du groupe', error);
        });
    // Fermer la modale et réinitialiser l'état après la création du groupe
    closeModal();
    toggleGroupCreationMode();
    loadUserGroups()
}


function loadUserGroups() {
    axios.get('/api/user-groups')
        .then(response => {
            const groups = response.data.groups;
            const groupsContainer = document.querySelector('.flex.flex-col.-mx-4'); // Sélecteur de la div où afficher les groupes

            // Nettoie la liste actuelle des groupes
            groupsContainer.innerHTML = '';

            groups.forEach(group => {
                // Crée un nouvel élément pour chaque groupe
                const groupElement = document.createElement('div');
                groupElement.classList.add('relative', 'flex', 'flex-row', 'items-center', 'p-4');

                // Temps depuis la dernière activité du groupe (exemple statique '5 min')
                const timeElement = document.createElement('div');
                timeElement.classList.add('absolute', 'text-xs', 'text-gray-500', 'right-0', 'top-0', 'mr-4', 'mt-3');
                timeElement.textContent = '5 min'; // Vous devrez remplacer cela par une valeur dynamique si disponible

                // Icône du groupe (exemple statique avec 'T')
                const iconElement = document.createElement('div');
                iconElement.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'bg-blue-500', 'text-blue-300', 'font-bold', 'flex-shrink-0');
                iconElement.textContent = group.name.charAt(0)

                // Nom et description du groupe
                const groupInfoElement = document.createElement('div');
                groupInfoElement.classList.add('flex', 'flex-col', 'flex-grow', 'ml-3');
                const groupNameElement = document.createElement('div');
                groupNameElement.classList.add('text-sm', 'font-medium');
                groupNameElement.textContent = group.name;
                const groupDescElement = document.createElement('div');
                groupDescElement.classList.add('text-xs', 'truncate', 'w-40');
/*
                groupDescElement.textContent = group.description; // Remplacez par la description du groupe si disponible
*/

                // Nombre de nouveaux messages (exemple statique '5')
                const newMessagesElement = document.createElement('div');
                newMessagesElement.classList.add('flex-shrink-0', 'ml-2', 'self-end', 'mb-1');
                const messagesCountElement = document.createElement('span');
                messagesCountElement.classList.add('flex', 'items-center', 'justify-center', 'h-5', 'w-5', 'bg-red-500', 'text-white', 'text-xs', 'rounded-full');
                messagesCountElement.textContent = '5'; // Remplacez par le nombre réel de nouveaux messages si disponible

                // Assemblage des éléments
                //newMessagesElement.appendChild(messagesCountElement);
                groupInfoElement.appendChild(groupNameElement);
                groupInfoElement.appendChild(groupDescElement);
                //groupElement.appendChild(timeElement);
                groupElement.appendChild(iconElement);
                groupElement.appendChild(groupInfoElement);
                groupElement.appendChild(newMessagesElement);
                groupElement.addEventListener('click', () => joinGroupChat(group.id, groupNameElement.textContent));

                // Ajout de l'élément de groupe au conteneur
                groupsContainer.appendChild(groupElement);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des groupes', error));
}

function loadPreviousMessages(groupId) {
    axios.get(`/group-chat/${groupId}/messages`)
        .then(response => {
            const messages = response.data.messages;
            console.log(messages)
            const chatDiv = document.querySelector('.grid.grid-cols-12.gap-y-2'); // Sélection de la grille où afficher les messages
            chatDiv.innerHTML = ''; // Efface les messages précédents avant de charger les nouveaux

            messages.forEach(message => {
                appendMessageToChat(message.content, message.author); // Assurez-vous que ces propriétés correspondent à votre objet de message
            });
        })
        .catch(error => console.error('Erreur lors du chargement des messages', error));
}

function joinGroupChat(groupId, groupName) {
    if (currentGroupId && groupChannels[currentGroupId]) {
        window.Echo.leave(`group.${currentGroupId}`);
        delete groupChannels[currentGroupId];
    }

    currentGroupId = groupId;

    // Met à jour l'en-tête du groupe avec les détails du groupe sélectionné
    const groupNameElement = document.getElementById('groupName');
    const groupLogoElement = document.getElementById('groupLogo');

    groupNameElement.textContent = groupName; // Met à jour le nom du groupe
    groupLogoElement.textContent = groupName.charAt(0); // Utilise la première lettre du nom du groupe comme logo
    groupLogoElement.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'bg-blue-500', 'text-blue-300', 'text-m', 'rounded-full');

    // Efface les messages précédents
    document.querySelector('.grid.grid-cols-12.gap-y-2').innerHTML = '';

    // Charge les messages précédents pour le groupe sélectionné
    loadPreviousMessages(groupId);

    // Abonne au canal du groupe
    subscribeToGroupChannel(groupId);
}
function subscribeToGroupChannel(groupId) {
    console.log("je suis dasn subscribeToGroupChannel ")
    if (!groupChannels[groupId]) {
        groupChannels[groupId] = window.Echo.private(`group.${groupId}`)
            .listen('.NewMessage', (e) => {
                appendMessageToChat(e.message.content, e.message.author);
                // Assurez-vous que ces propriétés correspondent à votre objet de message
            });
    }
}




function sendMessage() {
    const messageInput = document.querySelector('input[type="text"]'); // Assurez-vous que le sélecteur est correct pour votre champ de saisie de message
    const messageContent = messageInput.value;

    if (!messageContent.trim() || !currentGroupId) {
        console.error('Message content is empty or no group selected');
        return;
    }

    axios.post(`/group-chat/${currentGroupId}/send`, {
        groupId: currentGroupId, // Assurez-vous que cette partie de votre API attend `groupId`
        message: messageContent
    })
        .then(() => {
            appendMessageToChat(messageContent, 'Vous'); // 'Vous' ou le nom d'utilisateur actuel
            messageInput.value = ''; // Efface le champ de saisie après l'envoi
        })
        .catch(error => {
            console.error('Erreur d\'envoi', error);
        });
}


// Liez cette fonction au bouton d'envoi ou à l'événement 'submit' du formulaire de message.



function appendMessageToChat(messageContent, authorName) {
    console.log(authorName)
    const chatDiv = document.querySelector('.grid.grid-cols-12.gap-y-2'); // Sélection de la grille où afficher les messages
    const messageElement = document.createElement('div');
    messageElement.classList.add('col-start-1', 'col-end-8', 'p-3', 'rounded-lg');

    const flexDiv = document.createElement('div');
    flexDiv.classList.add('flex', 'flex-row', 'items-center');

    const authorDiv = document.createElement('div');
    authorDiv.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'bg-indigo-500', 'flex-shrink-0');
    authorDiv.textContent = authorName; // Utilisez la première lettre du nom comme icône

    const messageContentDiv = document.createElement('div');
    messageContentDiv.classList.add('relative', 'ml-3', 'text-sm', 'bg-white', 'py-2', 'px-4', 'shadow', 'rounded-xl');
    messageContentDiv.textContent = messageContent;

    flexDiv.appendChild(authorDiv);
    flexDiv.appendChild(messageContentDiv);
    messageElement.appendChild(flexDiv);

    chatDiv.appendChild(messageElement);
}


// Exemple de fonction pour démarrer une conversation (à remplacer par votre logique réelle)
function startConversation(userId) {
    console.log("Démarrer une conversation avec l'utilisateur ID:", userId);
    // Ajoutez ici votre logique pour démarrer une conversation avec l'utilisateur sélectionné
    closeModal();
}




function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    loadUsers()

}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}
