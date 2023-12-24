<?php
include('connect_db.php');

if (isset($_POST['submit'])){
    $lastname = ucfirst(strtolower(trim($_POST['lastname']))); // Mettre le nom en majuscule
    $firstname = ucfirst(strtolower(trim($_POST['firstname']))); // Mettre le prénom en majuscule
    $email = strtolower(trim($_POST['email'])); // Mettre l'email en minuscule
    $password = $_POST['password'];
    $id_status = $_POST['id_status'];

    // verfi champs remplis
    if (empty($lastname) || empty($firstname) || empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs.\n";
    } else {
        // verif compte existe pas 
        $checkEmailQuery = "SELECT id FROM user WHERE email = '$email'";
        $checkEmailResult = $conn->query($checkEmailQuery);

        if ($checkEmailResult->num_rows > 0) {
            echo "L'email existe déjà. Choisissez un autre email.\n";
        } else {
            // verif complexité du mot de passe
            if (strlen($password) < 8 || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
                echo "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule et un chiffre.\n";
            } else {
                // Hasher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // insertion
                $sql = "INSERT INTO `user` (id, lastname, firstname, email, password, id_status) VALUES ('', '$lastname', '$firstname', '$email', '$hashedPassword', '$id_status')";

                // verif requet fonctionne
                if ($conn->query($sql) === TRUE) {
                    echo "Inscription réussie !\n";
                    header('Location: ../connexion.php');
                } else {
                    echo "Erreur lors de l'inscription : " . $conn->error ."\n";
                }
            }
        }
    }
}
?>