import axios from "axios";

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

    showConversationList()


    const backButton = document.getElementById('backButton'); // L'identifiant du bouton de retour
    backButton.addEventListener('click', showConversationList);

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
        startRefreshingConversations();
        loadUnreadMessagesCount()






        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            console.log("new message")
            loadUserGroups();
            loadUserConversation();
        });

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

function showConversationList() {
    const conversationList = document.querySelector('.conversation-list');
    const conversation = document.querySelector('.conversation');
    conversationList.classList.remove('hidden');
    conversation.classList.add('hidden');
}

// Cette fonction montre une conversation spécifique et cache la liste des conversations
function showConversation() {
    console.log("show")
    const conversationList = document.querySelector('.conversation-list');
    const conversation = document.querySelector('.conversation');
    conversationList.classList.add('hidden');
    conversation.classList.remove('hidden');
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


    axios.post('/create-group', {
        groupName: groupName,
        userIds: selectedUserIds
    })
        .then(() => {
            loadUserGroups()

        })
        .catch(error => {
            console.error('Erreur lors de la création du groupe', error);
        });
    // Fermer la modale et réinitialiser l'état après la création du groupe
    closeModal();
    toggleGroupCreationMode();
}


function loadUserGroups() {


    axios.get('/api/user-groups?type=group')
        .then(response => {
            const groups = response.data.groups;
            const groupsContainer = document.querySelector('.flex.flex-col.-mx-4');

            console.log(response.data.groups)
            // Nettoyage du conteneur des groupes
            groupsContainer.innerHTML = '';


            groups.forEach(group => {
                // Création de l'élément de groupe
                const groupElement = document.createElement('div');
                groupElement.classList.add('flex', 'flex-row', 'items-center', 'p-4', 'relative');
                groupElement.setAttribute('data-group-id', group.id); // Attribut de données unique pour chaque groupe

                // Temps depuis la dernière activité
                const timeElement = document.createElement('div');
                timeElement.classList.add('absolute', 'text-xs', 'text-gray-500', 'right-0', 'top-0', 'mr-4', 'mt-3');
                timeElement.textContent = group.lastMessageTime || 'Un moment';
                timeElement.setAttribute('data-last-message-time', group.id);

                // Icône du groupe
                const iconElement = document.createElement('div');
                iconElement.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'bg-blue-500', 'text-blue-300', 'font-bold', 'flex-shrink-0');
                iconElement.textContent = group.name.charAt(0);

                // Nom du groupe et dernier message
                const groupInfoElement = document.createElement('div');
                groupInfoElement.classList.add('flex', 'flex-col', 'flex-grow', 'ml-3');
                const groupNameElement = document.createElement('div');
                groupNameElement.classList.add('text-sm', 'font-medium');
                groupNameElement.textContent = group.name;
                const lastMessageElement = document.createElement('div');
                lastMessageElement.classList.add('text-xs', 'truncate', 'w-40');
                lastMessageElement.textContent = group.lastMessageContent || 'Pas de messages';
                lastMessageElement.setAttribute('data-last-message', group.id);

                //bouton notif
                const boutonNOTIF = document.createElement('div');
                boutonNOTIF.classList.add('flex', 'flex-col', 'flex-grow', 'ml-3');
                const boutonElement = document.createElement('div');
                // Ajoutez un écouteur d'événements au bouton pour gérer les clics
                const groupId = group.id;
                fetch(`/notifications/{groupId}/{userId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const button = document.getElementById('notificationToggleButton'); // Assurez-vous que cet ID correspond à votre bouton
                        if (data.notifications_enabled) {
                            boutonElement.className = 'bg-green-500 hover:bg-blue-700 text-white py-2 px-4 rounded';
                            boutonElement.textContent = 'Son ON'; // Change le texte du bouton

                            boutonElement.addEventListener('click', function() {
                                console.log('Bouton cliqué!');
                                console.log(group.id);
                                console.log(globalUserId);

                                axios.post('/toggle-group-notification', {
                                    group_id: group.id, // L'ID du groupe, à dynamiser selon votre logique d'application
                                    user_id: globalUserId, // L'ID de l'utilisateur, généralement obtenu via l'authentification
                                    enable: false, // ou false, selon l'état actuel du bouton
                                })
                                    .then(response => {
                                        console.log(response.data.message);
                                        // Mettre à jour l'interface utilisateur en conséquence
                                    })
                                    .catch(error => {
                                        console.error("There was an error toggling the notification setting:", error);
                                    });

                            });
                        } else {
                            boutonElement.className = 'bg-red-500 hover:bg-blue-700 text-white py-2 px-4 rounded';
                            boutonElement.textContent = 'Son OFF'; // Change le texte du bouton
                            boutonElement.addEventListener('click', function() {
                                console.log('Bouton cliqué!');
                                console.log(group.id);
                                console.log(globalUserId);

                                axios.post('/toggle-group-notification', {
                                    group_id: group.id, // L'ID du groupe, à dynamiser selon votre logique d'application
                                    user_id: globalUserId, // L'ID de l'utilisateur, généralement obtenu via l'authentification
                                    enable: false, // ou false, selon l'état actuel du bouton
                                })
                                    .then(response => {
                                        console.log(response.data.message);
                                        // Mettre à jour l'interface utilisateur en conséquence
                                    })
                                    .catch(error => {
                                        console.error("There was an error toggling the notification setting:", error);
                                    });

                            });
                        }
                    })
                    .catch(error => console.error('Erreur lors de la récupération de l\'état des notifications:', error));

                // Ajoutez le bouton à l'élément div
                boutonNOTIF.appendChild(boutonElement);


                // Nombre de nouveaux messages
                const newMessagesElement = document.createElement('div');
                newMessagesElement.classList.add('flex-shrink-0', 'ml-2', 'self-end', 'mb-1');
                const messagesCountElement = document.createElement('span');
                messagesCountElement.classList.add('flex', 'items-center', 'justify-center', 'h-5', 'w-5', 'bg-red-500', 'text-white', 'text-xs', 'rounded-full');
                messagesCountElement.textContent = group.newMessagesCount || '';

                // Assemblage des éléments
                groupInfoElement.appendChild(groupNameElement);
                groupInfoElement.appendChild(lastMessageElement);

                if (group.newMessagesCount > 0) {
                    newMessagesElement.appendChild(messagesCountElement);
                }
                groupElement.appendChild(timeElement);
                groupElement.appendChild(iconElement);
                groupElement.appendChild(groupInfoElement);
                groupElement.appendChild(newMessagesElement);
                groupElement.appendChild(boutonNOTIF);
                // Ajout d'un écouteur d'événements pour le clic
                groupElement.addEventListener('click', () => joinGroupChat(group.id, groupNameElement.textContent));

                subscribeToAllGroupChannels(groups); // S'abonne à tous les groupes


                // Ajout de l'élément de groupe au conteneur
                groupsContainer.appendChild(groupElement);
            });
        })
        .catch(error => console.error('Erreur lors du chargement des groupes', error));
}

function loadPreviousMessages(groupId) {
    axios.get(`/group-chat/${groupId}/messages`)
        .then(response => {
            //console.log(response.data)
            const messages = response.data.messages;
            const chatDiv = document.querySelector('.grid.grid-cols-12.gap-y-2');
            chatDiv.innerHTML = ''; // Efface les messages précédents avant de charger les nouveaux

            messages.forEach(message => {
                // Vérifiez que vous avez bien les propriétés user_firstname et user_lastname dans chaque message
                appendMessageToChat(message.content, message.user_id, message.user_firstname, message.user_lastname, message.files);
                //
                //console.log(message.content, message.user_id, message.user_firstname, message.user_lastname, message.files)
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

    // Met à jour le moment de la dernière visite dans la base de données
    updateLastVisitedAt(groupId); // Mettre à jour la dernière visite

    // Charge les messages précédents pour le groupe sélectionné
    loadPreviousMessages(groupId);

    // Abonne au canal du groupe
    subscribeToGroupChannel(groupId);

    showConversation()
}
function subscribeToGroupChannel(groupId) {
    if (!groupChannels[groupId]) {
        groupChannels[groupId] = window.Echo.private(`group.${groupId}`)
            .listen('GroupChatMessageEvent', (e) => {
                appendMessageToChat(e.message.content,e.message.id, e.message.firstname, e.message.lastname,e.message.files);
                loadPreviousMessages(groupId);
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
            //console.log('User info loaded:', globalFirstname, globalLastname);
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des informations de l\'utilisateur', error);
        });
}


function sendMessage() {
    const messageInput = document.querySelector('input[type="text"]');
    const messageContent = messageInput.value;
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');

    if (!currentGroupId) {
        console.error('No group selected');
        return;
    }

    // Création d'un objet FormData
    const formData = new FormData();
    formData.append('groupId', currentGroupId);
    if (messageContent.trim()) {
        formData.append('message', messageContent);
    }

    // Ajouter chaque fichier sélectionné à formData
    Array.from(fileInput.files).forEach((file, index) => {
        formData.append(`files[${index}]`, file);
        console.log(file)
    });

    axios.post(`/group-chat/${currentGroupId}/send`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(() => {
            messageInput.value = '';
            previewContainer.innerHTML = ''; // Effacer l'aperçu
            fileInput.value = ''; // Réinitialiser l'input de fichier

            loadPreviousMessages(currentGroupId);
            triggerPushNotification(currentGroupId, messageContent, globalUserId);
            loadUserConversation()
            updateConversationPreview(currentGroupId)
        })
        .catch(error => {
            console.error('Erreur d\'envoi', error);
        });
}

// Le reste du code pour la gestion de l'input de fichier et de l'aperçu reste inchangé





// Liez cette fonction au bouton d'envoi ou à l'événement 'submit' du formulaire de message.


function appendMessageToChat(messageContent, authorID, authorFirstname, authorLastname,fileData) {



    //console.log(fileData)

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

    if (fileData && fileData.length > 0) {
        fileData.forEach(file => {
            const { file_path, file_type } = file; // Utilisez les clés correctes ici
            const fileElement = document.createElement('div');
            fileElement.className = 'mt-2';

            // Assurez-vous que file_type est défini avant de continuer
            if (file_type) {
                if (file_type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = file_path; // Utilisez file_path ici
                    img.className = 'max-w-full h-auto rounded-lg';
                    fileElement.appendChild(img);
                } else if (file_type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = file_path; // Utilisez file_path ici
                    video.controls = true;
                    video.className = 'max-w-full h-auto rounded-lg';
                    fileElement.appendChild(video);
                } else if (file_type.startsWith('audio/')) {
                    const audio = document.createElement('audio');
                    audio.src = file_path; // Utilisez file_path ici
                    audio.controls = true;
                    fileElement.appendChild(audio);
                } else {
                    const text = document.createElement('p');
                    text.textContent = 'Type de fichier non pris en charge';
                    fileElement.appendChild(text);
                }
            } else {
                const text = document.createElement('p');
                text.textContent = 'Aucun type de fichier disponible';
                fileElement.appendChild(text);
            }


            messageContentDiv.appendChild(fileElement);
        });

        // Bouton de téléchargement pour tous les fichiers
        const downloadAllBtn = document.createElement('button');
        downloadAllBtn.textContent = 'Enregistrer ⬇️';
        downloadAllBtn.classList.add('mt-2', 'text-sm', 'text-black', 'p-1', 'rounded');
        downloadAllBtn.onclick = () => {
            fileData.forEach((file) => {
                if (file.file_type.startsWith('image/') || file.file_type.startsWith('video/')) {
                    const link = document.createElement('a');
                    link.href = file.file_path;
                    link.download = ''; // Le navigateur utilisera le nom du fichier sur le serveur
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });
        };

        messageContentDiv.appendChild(downloadAllBtn);
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
        //console.log(response.data);

        // Construisez le nom du groupe en utilisant le prénom et le nom de l'autre utilisateur.
        const groupName = `${otherUser.firstname} ${otherUser.lastname}`; // Ajouté le lastname

        axios.post('/create-group', {
            groupName: groupName,
            userIds: [userOneId, userTwoId]
        })
            .then(response => {
                //('Conversation privée créée avec succès', response.data);
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
    loadUnreadMessagesCount().then(() => {

        axios.get('/api/user-groups?type=private')
        .then(response => {
            const groups = response.data.groups;
            const conversationsContainer = document.querySelector('.flex.flex-col.divide-y.h-full.overflow-y-auto.-mx-4');

            // Nettoyer la liste actuelle des conversations
            conversationsContainer.innerHTML = '';

            groups.forEach(group => {

                const unreadCount = group.unreadMessagesCount;


                const otherMember = group.members.find(member => member.id !== globalUserId);


                const conversationElement = document.createElement('div');
                conversationElement.classList.add('flex', 'flex-row', 'items-center', 'p-4', 'relative');
                conversationElement.setAttribute('data-conversation-id', group.id); // Attribut de données unique pour chaque conversation


                // Temps depuis la dernière activité
                const timeElement = document.createElement('div');
                timeElement.classList.add('absolute', 'text-xs', 'text-gray-500', 'right-0', 'top-0', 'mr-4', 'mt-3');
                timeElement.textContent = group.lastMessageTime || 'Un moment';
                timeElement.setAttribute('data-last-message-time', group.id);

                // Icône
                const iconElement = document.createElement('div');
                iconElement.classList.add('flex', 'items-center', 'justify-center', 'h-10', 'w-10', 'rounded-full', 'bg-pink-500', 'text-pink-300', 'font-bold', 'flex-shrink-0');
                iconElement.textContent = otherMember ? otherMember.firstname.charAt(0) : group.name.charAt(0);

                // Nom et dernier message
                const groupInfoElement = document.createElement('div');
                groupInfoElement.classList.add('flex', 'flex-col', 'flex-grow', 'ml-3');
                const groupNameElement = document.createElement('div');
                groupNameElement.classList.add('text-sm', 'font-medium');
                groupNameElement.textContent = otherMember ? `${otherMember.firstname} ${otherMember.lastname}` : 'Groupe inconnu';
                const lastMessageElement = document.createElement('div');
                lastMessageElement.classList.add('text-xs', 'truncate', 'w-40');
                lastMessageElement.textContent = group.lastMessageContent || 'Pas de messages';
                lastMessageElement.setAttribute('data-last-message', group.id);


                // Nombre de nouveaux messages
                    const newMessagesElement = document.createElement('div');
                    newMessagesElement.classList.add('flex-shrink-0', 'ml-2', 'self-end', 'mb-1');
                    const messagesCountElement = document.createElement('span');
                    messagesCountElement.classList.add('flex', 'items-center', 'justify-center', 'h-5', 'w-5', 'bg-red-500', 'text-white', 'text-xs', 'rounded-full');
                    messagesCountElement.textContent = unreadCount || ''; // À définir selon votre logique de comptage des nouveaux messages



                // Assemblage des éléments
                groupInfoElement.appendChild(groupNameElement);
                groupInfoElement.appendChild(lastMessageElement);
            if (unreadCount > 0) {
                newMessagesElement.appendChild(messagesCountElement);
            }
                conversationElement.appendChild(timeElement);
                conversationElement.appendChild(iconElement);
                conversationElement.appendChild(groupInfoElement);
                //conversationElement.appendChild(newMessagesElement);

                // Ajouter un écouteur d'événements pour rejoindre la conversation lors du clic
                conversationElement.addEventListener('click', () => joinGroupChat(group.id, groupNameElement.textContent));

                // Ajouter l'élément de conversation au conteneur
                conversationsContainer.appendChild(conversationElement);

                subscribeToAllGroupChannelsPrivate(groups)
            });
        })
        .catch(error => console.error('Erreur lors du chargement des groupes', error));
}
    )}

function triggerPushNotification(groupId, messageContent, globaluserId) {
    // Utilisez "Image" comme contenu par défaut si messageContent est vide
    const notificationContent = messageContent || "Image";

    //console.log("grouid " + groupId + " message " + messageContent + " globaluser " + globaluserId)



    axios.post('/api/send-notification-group', {
        groupId: groupId,
        message: notificationContent, // Utilisez notificationContent ici
        id_sender: globaluserId
    })
        .then(response => {
            /*console.log(notificationContent);
            console.log(response.data)*/

        })
        .catch(error => {
            console.error('Error triggering notification', error);
        });

}


function startRefreshingConversations() {
    loadUserConversation(); // Chargez les conversations immédiatement lors du premier appel

    // Définissez un intervalle pour relancer l'actualisation toutes les 1 minute (60000 millisecondes)
    setInterval(() => {
        loadUserGroups();
        loadUserConversation();    }, 60000);
}



function subscribeToAllGroupChannels(groups) {
    groups.forEach(group => {
        if (!groupChannels[group.id]) {
            groupChannels[group.id] = window.Echo.private(`group.${group.id}`)
                .listen('GroupChatMessageEvent', (e) => {
                    console.log(e.message);
                    updateGroupPreview(group.id, e.message.content);
                });
        }
    });
}


function updateGroupPreview(groupId, messageContent) {
    // Sélectionner l'élément de dernier message en utilisant l'attribut de données
    const lastMessageElement = document.querySelector(`[data-last-message="${groupId}"]`);
    if (lastMessageElement) {
        lastMessageElement.textContent = messageContent || 'Nouveau message';
    }

    // Mettre à jour le temps du dernier message de la même manière
    const lastMessageTimeElement = document.querySelector(`[data-last-message-time="${groupId}"]`);
    if (lastMessageTimeElement) {
        lastMessageTimeElement.textContent = new Date().toLocaleTimeString(); // ou toute autre logique de formatage de date
    }

}

function subscribeToAllGroupChannelsPrivate(groups) {
    groups.forEach(group => {
        if (!groupChannels[group.id]) {
            groupChannels[group.id] = window.Echo.private(`group.${group.id}`)
                .listen('GroupChatMessageEvent', (e) => {
                    console.log(e.message);
                    updateConversationPreview(group.id, e.message.content);
                });
        }
    });
}

function updateConversationPreview(conversationId) {
    loadUnreadMessagesCount().then(groupsWithUnreadCounts => {
        loadUserConversation()
        loadPreviousMessages(conversationId)

        // Trouver les données du groupe spécifique en utilisant son ID
        const groupData = groupsWithUnreadCounts.find(group => group.id === conversationId);
        const unreadCount = groupData ? groupData.unreadMessagesCount : 0;

        // Sélectionner les éléments de la conversation en utilisant les attributs de données
        const lastMessageElement = document.querySelector(`[data-conversation-id="${conversationId}"] [data-last-message]`);
        if (lastMessageElement) {
            lastMessageElement.textContent = groupData.lastMessageContent || 'Nouveau message';
        }

        const lastMessageTimeElement = document.querySelector(`[data-conversation-id="${conversationId}"] [data-last-message-time]`);
        if (lastMessageTimeElement) {
            lastMessageTimeElement.textContent = groupData.lastMessageTime || new Date().toLocaleTimeString();
        }

        // Sélectionnez l'élément pour afficher le compte des messages non lus
        const messagesCountElement = document.querySelector(`[data-conversation-id="${conversationId}"] .messages-count`);
        if (messagesCountElement) {
            if (unreadCount > 0) {
                messagesCountElement.textContent = unreadCount;
                messagesCountElement.classList.remove('hidden');
            } else {
                messagesCountElement.classList.add('hidden');
            }
        }

    }).catch(error => {
        console.error("Erreur lors du chargement du nombre de messages non lus:", error);
    });
}



function updateLastVisitedAt(groupId) {
    axios.post(`/api/groups/${groupId}/update-last-visited`) // Assurez-vous que cette route est définie dans votre backend
        .then(response => {
            console.log("Dernière visite mise à jour pour le groupe:", groupId);
            loadUserConversation()
        })
        .catch(error => {
            console.error("Erreur lors de la mise à jour de la dernière visite:", error);
        });
}



function loadUnreadMessagesCount() {
    // Retourner une promesse qui résoudra avec les données des groupes
    return new Promise((resolve, reject) => {
        axios.get('/api/user-groups')
            .then(response => {
                const groups = response.data.groups;
                const groupsWithUnreadCounts = groups.map(group => {
                    // Assurez-vous que lastMessageContent et lastMessageTime sont renvoyés par votre API
                    return {
                        id: group.id,
                        unreadMessagesCount: group.unreadMessagesCount,
                        lastMessageContent: group.lastMessageContent,
                        lastMessageTime: group.lastMessageTime,
                    };
                });

                // Résoudre la promesse avec les données de groupes mises à jour
                resolve(groupsWithUnreadCounts);
            })
            .catch(error => {
                console.error("Erreur lors du chargement des groupes et du nombre de messages non lus:", error);
                reject(error); // Rejeter la promesse en cas d'erreur
            });
    });
}





function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    loadUsers()

}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

