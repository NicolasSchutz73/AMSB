<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post" action="function/connexion.php">
        <label for="email">Email :</label>
        <input type="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <input type="submit" name="login_submit" value="Se connecter">
    </form>
</body>
</html>

<?php
session_start();
if(isset($_SESSION['user_id']) === True){
    echo "connecter \n";
}else{
    echo "pas connecter \n";
}
?>
