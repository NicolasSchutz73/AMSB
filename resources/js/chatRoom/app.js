import axios from 'axios';
import Echo from 'laravel-echo';

let selectedUsers = [];
let currentGroupId = null;
let groupChannels = {};



document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    loadUserGroups();
    initializePusherListeners();

    document.getElementById('createGroupButton').addEventListener('click', createGroup);
    document.getElementById('submitButton').addEventListener('click', sendMessage);
});



// Appeler cette fonction pour forcer la réinitialisation


function initializePusherListeners() {
    // Initialiser les écouteurs Pusher ici si nécessaire
}

function loadUsers() {
    axios.get('../users')
        .then(response => {
            const users = response.data.data || response.data;
            const userList = document.getElementById('userList');
            users.forEach(user => appendUserToList(user, userList));
        })
        .catch(error => console.error(error));
}

function appendUserToList(user, userList) {
    const userElement = document.createElement('div');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.value = user.id;
    checkbox.addEventListener('change', (event) => {
        handleUserSelection(event, user.id);
    });

    userElement.appendChild(checkbox);
    userElement.append(` ${user.firstname} ${user.lastname}`);
    userList.appendChild(userElement);
}

function handleUserSelection(event, userId) {
    if (event.target.checked) {
        selectedUsers.push(userId);
    } else {
        selectedUsers = selectedUsers.filter(id => id !== userId);
    }
}

function loadUserGroups() {
    axios.get('/user-groups')
        .then(response => {
            const groups = response.data.groups;
            const groupList = document.getElementById('groupList');
            groups.forEach(group => addGroupToList(group, groupList));
        })
        .catch(error => console.error('Erreur lors de la récupération des groupes', error));
}

function addGroupToList(group, groupList) {
    const groupElement = document.createElement('div');
    groupElement.innerText = group.name;
    groupElement.classList.add('group-item');
    groupElement.dataset.groupId = group.id;
    groupElement.addEventListener('click', () => joinGroupChat(group.id));
    groupList.appendChild(groupElement);
}



function clearChat() {
    document.getElementById('chat').innerHTML = '';
}

function updateGroupChatHeader(groupId) {
    const groupElement = document.querySelector(`.group-item[data-group-id="${groupId}"]`);
    document.getElementById('groupChatHeader').innerText = `Chat de groupe : ${groupElement ? groupElement.innerText : ''}`;
}

function loadPreviousMessages(groupId) {
    axios.get(`/api/groups/${groupId}/messages`)
        .then(response => {
            response.data.messages.forEach(message => {
                appendMessageToChat(message.content);
            });
        })
        .catch(error => console.error(error));
}

function joinGroupChat(groupId) {
    // Désabonnement de l'ancien groupe, si nécessaire
    if (currentGroupId && groupChannels[currentGroupId]) {
        window.Echo.leave(`group.${currentGroupId}`);
        delete groupChannels[currentGroupId];
    }

    // Mise à jour de l'ID du groupe actuel
    currentGroupId = groupId;

    // Effacer le chat précédent
    clearChat();

    // Mettre à jour l'en-tête du chat
    updateGroupChatHeader(groupId);

    // Charger les messages précédents
    loadPreviousMessages(groupId);

    // Abonner au nouveau groupe
    subscribeToGroupChannel(groupId)
}

function subscribeToGroupChannel(groupId) {
    console.log('1')
    if (!groupChannels[groupId]) {
        console.log(`Abonnement au canal : group.${groupId}`);
        groupChannels[groupId] =
            window.Echo.private(`group.${groupId}`)
                .listen('GroupChatMessageEvent', (e) => {
                    console.log(`Message reçu sur le canal : group.${groupId}`);
                    appendMessageToChat(e.message.content, e.message.authorName);
                });
    }
}


function appendMessageToChat(messageContent, authorName){
    const chatDiv = document.getElementById('chat');
    const messageElement = document.createElement('div');
    messageElement.classList.add('p-3', 'rounded', 'bg-blue-100', 'shadow-sm');

    // Ajout du nom de l'auteur
    const authorElement = document.createElement('div');
    authorElement.classList.add('text-sm', 'text-gray-600');
    authorElement.innerText = authorName;
    messageElement.appendChild(authorElement);

    // Ajout du contenu du message
    const contentElement = document.createElement('div');
    contentElement.innerText = messageContent;
    messageElement.appendChild(contentElement);

    chatDiv.appendChild(messageElement);
}


function sendMessage() {
    const messageInput = document.getElementById("message");
    if (!messageInput.value || !currentGroupId) return;

    axios.post(`/group-chat/${currentGroupId}/send`, {
        groupId: currentGroupId,
        message: messageInput.value
    })
        .then(() => {
            console.log('Message envoyé');
            messageInput.value = '';
        })
        .catch(error => {
            console.error('Erreur d\'envoi', error);
        });


}

function createGroup() {
    const groupName = document.getElementById('groupName').value;
    if (!groupName) {
        alert('Veuillez entrer un nom de groupe.');
        return;
    }

    // Récupère les IDs des utilisateurs sélectionnés
    const selectedUserIds = Array.from(document.querySelectorAll('#userList input[type="checkbox"]:checked')).map(cb => cb.value);

    axios.post('/create-group', {
        groupName: groupName,
        userIds: selectedUserIds
    })
        .then(() => {
            console.log('Groupe créé avec succès');
            loadUserGroups(); // Appelle cette fonction pour recharger et afficher les groupes
        })
        .catch(error => {
            console.error('Erreur lors de la création du groupe', error);
        });
}

// Assurez-vous que cette fonction est bien appelée lors du clic sur le bouton de création de groupe
document.getElementById('createGroupButton').addEventListener('click', createGroup);
