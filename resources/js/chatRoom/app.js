import axios from "axios";

document.addEventListener('DOMContentLoaded', function() {
    axios.get('../users')
        .then(response => {
            console.log(response.data); // Ajoute ceci pour déboguer
            const users = response.data.data || response.data; // Gère les réponses paginées et non paginées
            const userList = document.getElementById('userList');

            if (Array.isArray(users)) { // Vérifie si 'users' est bien un tableau
                users.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.innerText = `${user.firstname} ${user.lastname}`;
                    userList.appendChild(userElement);
                });
            }
        })
        .catch(error => console.error(error));
});
