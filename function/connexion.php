<?php
include('connect_db.php');

if (isset($_POST['login_submit'])){
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    // Verif email existe dans db
    $checkEmailQuery = "SELECT id, lastname, firstname, email, id_status, password FROM user WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        // verif mdp
        $userData = $checkEmailResult->fetch_assoc();
        $hashedPassword = $userData['password'];

        if (password_verify($password, $hashedPassword)) {
            // mdp correct
            session_start();
            // variable de session
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['lastname'] = $userData['lastname'];
            $_SESSION['firstname'] = $userData['firstname'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['id_status'] = $userData['id_status'];

            echo "Connexion user réussie !";
            header('Location: ../index.php');
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }
}
?>