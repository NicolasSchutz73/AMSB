import axios from "axios";

let selectedUsers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Charge les utilisateurs pour créer un groupe
    loadUsers();

    // Charge les groupes auxquels l'utilisateur appartient
    loadUserGroups();

    document.getElementById('createGroupButton').addEventListener('click', createGroup);
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
            const groupElement = document.createElement('div');
            groupElement.innerText = group.name;
            groupList.appendChild(groupElement);

            // Écoute sur les canaux des groupes
            window.Echo.private(`group.${group.id}`)
                .listen('.GroupChatMessageEvent', (e) => {
                    console.log(e.message);
                    // Affiche le message dans l'interface utilisateur
                });
        });
    }).catch(error => console.error('Erreur lors de la récupération des groupes', error));
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

