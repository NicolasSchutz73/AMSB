 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post" action="function/inscription.php">
        <label for="lastname">Nom :</label>
        <input type="text" name="lastname" required><br>

        <label for="firstname">Prénom :</label>
        <input type="text" name="firstname" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <label for="id_status">ID Status :</label>
        <input type="text" name="id_status" required><br>

        <input type="submit" name="submit" value="S'inscrire">
    </form>
</body>
</html>
<?php
session_start();
if(isset($_SESSION['user_id']) === True){
    echo 'connecter';
}else{
    echo 'pas connecter';
}
?>
