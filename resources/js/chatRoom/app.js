import axios from 'axios';
import Echo from 'laravel-echo';

// Variables globales
let selectedUsers = [];
let currentGroupId = null; // ID du groupe actuellement sélectionné
let groupChannels = {}; // Pour stocker les références aux canaux de groupe

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    loadUsers();
    loadUserGroups();

    // Écouteurs d'événements
    document.getElementById('createGroupButton').addEventListener('click', createGroup);
    document.getElementById('submitButton').addEventListener('click', sendMessage);
});


function loadUsers() {
    axios.get('../users')
        .then(response => {
            const users = response.data.data || response.data;
            const userList = document.getElementById('userList');

            if (Array.isArray(users)) {
                users.forEach(user => {
                    const userElement = document.createElement('div');
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = user.id;

                    // Gestionnaire d'événements pour la case à cocher
                    checkbox.addEventListener('change', (event) => {
                        if (event.target.checked) {
                            selectedUsers.push(user.id); // Ajoute l'utilisateur si coché
                        } else {
                            selectedUsers = selectedUsers.filter(id => id !== user.id); // Supprime l'utilisateur si décoché
                        }
                    });

                    userElement.appendChild(checkbox);
                    userElement.append(` ${user.firstname} ${user.lastname}`);
                    userList.appendChild(userElement);
                });
            }
        })
        .catch(error => console.error(error));
}

function loadUserGroups() {
    axios.get('/api/user-groups').then(response => {
        const groups = response.data.groups;
        const groupList = document.getElementById('groupList');

        groups.forEach(group => {
            addGroupToList(group);
        });
    }).catch(error => console.error('Erreur lors de la récupération des groupes', error));
}
function addGroupToList(group) {
    const groupElement = document.createElement('div');
    groupElement.innerText = group.name;
    groupElement.classList.add('group-item');
    groupElement.dataset.groupId = group.id;
    document.getElementById('groupList').appendChild(groupElement);

    groupElement.addEventListener('click', function() {
        joinGroupChat(this.dataset.groupId);
    });
}
function joinGroupChat(groupId) {
    currentGroupId = groupId;
    const chatDiv = document.getElementById('chat');
    chatDiv.innerHTML = ''; // Effacer les messages précédents
    console.log('Rejoindre le groupe de chat', groupId);

    // Mettre à jour l'en-tête avec le nom du groupe
    const groupElement = document.querySelector(`.group-item[data-group-id="${groupId}"]`);
    const groupName = groupElement ? groupElement.innerText : '';
    document.getElementById('groupChatHeader').innerText = `Chat de groupe : ${groupName}`;

    // Charger les messages précédents
    axios.get(`/api/groups/${groupId}/messages`).then(response => {
        const messages = response.data.messages;
        messages.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.innerText = message.content;
            chatDiv.appendChild(messageElement);
        });
    }).catch(error => console.error(error));

    // S'abonner au canal du groupe pour les nouveaux messages
    if (!groupChannels[groupId]) {
        groupChannels[groupId] = window.Echo.private(`group.${groupId}`)
            .listen('.GroupChatMessageEvent', (e) => {
                if (groupId === currentGroupId) {
                    const messageElement = document.createElement('div');
                    messageElement.innerText = e.message.content;
                    chatDiv.appendChild(messageElement);
                }
            });
    }
}

function sendMessage() {
    const messageInput = document.getElementById("message");
    const chatDiv = document.getElementById('chat');
    if (!messageInput.value || !currentGroupId) return;

    axios.post(`/group-chat/${currentGroupId}/send`, {
        groupId: currentGroupId,
        message: messageInput.value
    }).then(response => {
        console.log('Message envoyé', response);
        const messageElement = document.createElement('div');
        messageElement.innerText = messageInput.value;
        chatDiv.appendChild(messageElement);
        messageInput.value = '';
    }).catch(error => {
        console.error('Erreur d\'envoi', error);
    });
}





// Fonction pour gérer la soumission du formulaire de création de groupe
function createGroup() {
    const groupName = document.getElementById('groupName').value;
    if (!groupName) {
        alert('Veuillez entrer un nom de groupe.');
        return;
    }

    axios.post('/create-group', {
        groupName: groupName,
        userIds: selectedUsers
    })
        .then(response => {
            console.log('Groupe créé avec succès', response);
            // Réinitialiser le formulaire et la sélection
            document.getElementById('groupName').value = '';
            selectedUsers = [];
            // Désélectionner toutes les cases à cocher
            document.querySelectorAll('#userList input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        })
        .catch(error => {
            console.error('Erreur lors de la création du groupe', error);
        });
}
