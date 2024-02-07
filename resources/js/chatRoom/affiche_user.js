import axios from "axios";
import Echo from 'laravel-echo';

let currentGroupId = null;
let groupChannels = {};
let globalFirstname = '';
let globalLastname = '';
let globalUserId = '';
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

    messageInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            sendMessage(messageInput.value); // Simule un clic sur le bouton d'envoi
            messageInput.value = ''; // Efface le champ après l'envoi
        }
    });


    getUserInfoAsync().then(() => {
        loadUserGroups();
        loadUserConversation();
    }).catch(error => {
        console.error('Erreur lors de la récupération des informations utilisateur', error);
    });


});

let isGroupCreationActive = false;

function getUserInfoAsync() {
    return new Promise((resolve, reject) => {
        try {
            getUserInfo(); // votre fonction actuelle qui ne retourne pas de promesse
            resolve(); // Si tout va bien, résolvez la promesse
        } catch (error) {
            reject(error); // Si une erreur se produit, rejetez la promesse
        }
    });
}





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
    axios.get('/api/user-groups?type=group')
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
            const chatDiv = document.querySelector('.grid.grid-cols-12.gap-y-2');
            chatDiv.innerHTML = ''; // Efface les messages précédents avant de charger les nouveaux

            messages.forEach(message => {
                // Vérifiez que vous avez bien les propriétés user_firstname et user_lastname dans chaque message
                appendMessageToChat(message.content, message.user_id, message.user_firstname, message.user_lastname);
                console.log(message.content, message.user_id, message.user_firstname, message.user_lastname)
            });
            const lastMessageElement = chatDiv.lastElementChild;
            if (lastMessageElement) {
                lastMessageElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
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
    if (!groupChannels[groupId]) {
        groupChannels[groupId] = window.Echo.private(`group.${groupId}`)
            .listen('GroupChatMessageEvent', (e) => {
                console.log(e.message.content,e.message.id, e.message.firstname, e.message.lastname)
                appendMessageToChat(e.message.content,e.message.id, e.message.firstname, e.message.lastname);
            });
    }
}



function getUserInfo() {
    axios.get('/userinfo')
        .then(response => {
            // Stocker le prénom et le nom dans les variables globales
            globalFirstname = response.data.firstname;
            globalLastname = response.data.lastname;
            globalUserId = response.data.id
            console.log('User info loaded:', globalFirstname, globalLastname);
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des informations de l\'utilisateur', error);
        });
}


function sendMessage() {
    const messageInput = document.querySelector('input[type="text"]');
    const messageContent = messageInput.value;

    if (!messageContent.trim() || !currentGroupId) {
        console.error('Message content is empty or no group selected');
        return;
    }

    axios.post(`/group-chat/${currentGroupId}/send`, {
        groupId: currentGroupId,
        message: messageContent
    })
        .then(() => {
            //appendMessageToChat(messageContent, globalUserId, globalFirstname, globalLastname);
            messageInput.value = '';
            loadPreviousMessages(currentGroupId)
            triggerPushNotification(currentGroupId, messageContent, globalUserId)

        })
        .catch(error => {
            console.error('Erreur d\'envoi', error);
        });
}




// Liez cette fonction au bouton d'envoi ou à l'événement 'submit' du formulaire de message.


function appendMessageToChat(messageContent, authorID, authorFirstname, authorLastname) {
    const chatDiv = document.querySelector('.grid.grid-cols-12.gap-y-2');
    const messageElement = document.createElement('div');

    // Déterminez si le message a été envoyé par l'utilisateur actuel
    const isCurrentUserMessage = authorID === globalUserId;

    // Appliquez des classes conditionnelles pour aligner les messages à droite ou à gauche
    if (isCurrentUserMessage) {
        messageElement.classList.add('col-start-6', 'col-end-13', 'p-3', 'rounded-lg', 'self-end', 'text-right');
    } else {
        messageElement.classList.add('col-start-1', 'col-end-8', 'p-3', 'rounded-lg', 'self-start', 'text-left');
    }

    const authorInfoDiv = document.createElement('div');
    authorInfoDiv.classList.add('mb-2', 'text', 'italic', 'text-gray-600', 'text-xs');
    authorInfoDiv.textContent = isCurrentUserMessage ? 'Vous' : `${authorFirstname} ${authorLastname}`;

    const flexDiv = document.createElement('div');
    // Appliquez 'justify-end' pour aligner à droite si c'est l'utilisateur actuel
    flexDiv.classList.add('flex', 'items-center', isCurrentUserMessage ? 'justify-end' : 'justify-start');

    const initials = `${authorFirstname ? authorFirstname.charAt(0) : ''}${authorLastname ? authorLastname.charAt(0) : ''}`;
    const authorDiv = document.createElement('div');
    authorDiv.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'text-white', 'font-bold');
    authorDiv.style.backgroundColor = isCurrentUserMessage ? '#4F46E5' : '#CBD5E1';

    authorDiv.textContent = initials.toUpperCase() || 'U';

    const messageContentDiv = document.createElement('div');
    messageContentDiv.classList.add('relative', 'text-sm', 'bg-white', 'py-2', 'px-4', 'shadow', 'rounded-xl');
    messageContentDiv.textContent = messageContent;

    // Inversez l'ordre d'ajout pour les messages de l'utilisateur actuel
    if (isCurrentUserMessage) {
        flexDiv.appendChild(authorDiv);
        flexDiv.appendChild(messageContentDiv);
    } else {
        flexDiv.appendChild(messageContentDiv);
        flexDiv.appendChild(authorDiv);
    }

    messageElement.appendChild(authorInfoDiv);
    messageElement.appendChild(flexDiv);

    chatDiv.appendChild(messageElement);

    const lastMessageElement = chatDiv.lastElementChild;
    if (lastMessageElement) {
        lastMessageElement.scrollIntoView({  block: 'end' });
    }
}




function startConversation(userId) {
    axios.get(`/check-group/${globalUserId}/${userId}`)
        .then(response => {
            if (response.data.groupId) {
                // Un groupe existant a été trouvé, rejoignez-le
                joinGroupChat(response.data.groupId, response.data.groupName);
            } else {
                // Aucun groupe n'existe, créez-en un nouveau
                createPrivateGroup(globalUserId, userId);
            }
        })
        .catch(error => {
            console.error("Erreur lors de la vérification ou de la création du groupe privé", error);
        });

    closeModal();
}



function createPrivateGroup(userOneId, userTwoId) {
    axios.get(`/api/user-details/${userTwoId}`).then(response => {
        const otherUser = response.data;
        console.log(response.data);

        // Construisez le nom du groupe en utilisant le prénom et le nom de l'autre utilisateur.
        const groupName = `${otherUser.firstname} ${otherUser.lastname}`; // Ajouté le lastname

        axios.post('/create-group', {
            groupName: groupName,
            userIds: [userOneId, userTwoId]
        })
            .then(response => {
                console.log('Conversation privée créée avec succès', response.data);
                joinGroupChat(response.data.group.id, groupName);
                loadUserConversation()
            })
            .catch(error => {
                console.error('Erreur lors de la création de la conversation privée', error);
            });
    }).catch(error => {
        console.error('Erreur lors de la récupération des informations de l\'utilisateur', error);
    });
}


function loadUserConversation() {
    axios.get('/api/user-groups?type=private')
        .then(response => {
            console.log(response.data)
            const groups = response.data.groups;
            const conversationsContainer = document.querySelector('.flex.flex-col.divide-y.h-full.overflow-y-auto.-mx-4'); // Sélecteur de la div pour les conversations

            // Nettoie la liste actuelle des conversations
            conversationsContainer.innerHTML = '';

            groups.forEach(group => {
                const otherMember = group.members.find(member => member.id !== globalUserId);
                console.log(otherMember)
                console.log(globalUserId)

                // Crée un nouvel élément pour chaque conversation
                const conversationElement = document.createElement('div');
                conversationElement.classList.add('flex', 'flex-row', 'items-center', 'p-4', 'relative');

                // Temps depuis la dernière activité (exemple statique '2 hours ago')
                const timeElement = document.createElement('div');
                timeElement.classList.add('absolute', 'text-xs', 'text-gray-500', 'right-0', 'top-0', 'mr-4', 'mt-3');
                timeElement.textContent = '2 hours ago'; // Remplacer par une valeur dynamique si disponible

                // Icône (exemple statique avec 'T')
                const iconElement = document.createElement('div');
                iconElement.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'bg-pink-500', 'text-pink-300', 'font-bold', 'flex-shrink-0');
                iconElement.textContent = group.name.charAt(0); // Utilisez la première lettre du nom du groupe comme icône

                // Nom et dernier message
                const groupInfoElement = document.createElement('div');
                groupInfoElement.classList.add('flex', 'flex-col', 'flex-grow', 'ml-3');
                const groupNameElement = document.createElement('div');
                groupNameElement.classList.add('text-sm', 'font-medium');
                groupNameElement.textContent = otherMember ? `${otherMember.firstname} ${otherMember.lastname}` : 'Unknown';
                const lastMessageElement = document.createElement('div');
                lastMessageElement.classList.add('text-xs', 'truncate', 'w-40');
                lastMessageElement.textContent = 'Good after noon! how can i help you?'; // Dernier message, remplacer par la donnée dynamique si disponible

                // Nombre de nouveaux messages (exemple statique '3')
                const newMessagesElement = document.createElement('div');
                newMessagesElement.classList.add('flex-shrink-0', 'ml-2', 'self-end', 'mb-1');
                const messagesCountElement = document.createElement('span');
                messagesCountElement.classList.add('flex', 'items-center', 'justify-center', 'h-5', 'w-5', 'bg-red-500', 'text-white', 'text-xs', 'rounded-full');
                messagesCountElement.textContent = '3'; // Remplacer par le nombre réel de nouveaux messages si disponible

                // Assemblage des éléments
                groupInfoElement.appendChild(groupNameElement);
                groupInfoElement.appendChild(lastMessageElement);
                newMessagesElement.appendChild(messagesCountElement);
                conversationElement.appendChild(timeElement);
                conversationElement.appendChild(iconElement);
                conversationElement.appendChild(groupInfoElement);
                conversationElement.appendChild(newMessagesElement);

                // Ajouter un écouteur d'événements pour rejoindre la conversation lors du clic
                conversationElement.addEventListener('click', () => joinGroupChat(group.id, groupNameElement.textContent));

                // Ajouter l'élément de conversation au conteneur
                conversationsContainer.appendChild(conversationElement);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des groupes', error));
}

// Dans votre script JS inclus dans une vue Blade
function sendNotificationToSW(title, body, icon) {
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        navigator.serviceWorker.controller.postMessage({
            type: 'SHOW_NOTIFICATION',
            title: title,
            body: body,
            icon: icon
        });
    } else {
        console.log('Service Worker not registered or page not controlled by SW');
    }
}

function triggerPushNotification(groupId, messageContent, globaluserId) {
    axios.post('/api/send-notification-group', {
        groupId: groupId,
        message: messageContent,
        id_sender : globaluserId

    })
        .then(response => {
            console.log('Notification triggered successfully');
            console.log(response.data)
        })
        .catch(error => {
            console.error('Error triggering notification', error);
        });
}









function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    loadUsers()

}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}





