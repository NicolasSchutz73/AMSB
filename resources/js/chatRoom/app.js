import axios from "axios";

let selectedUsers = [];

document.addEventListener('DOMContentLoaded', function() {
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

    document.getElementById('createGroupButton').addEventListener('click', createGroup);

});


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

