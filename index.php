<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <a href="inscription.php">insc</a>
    <a href="connexion.php">conn</a>
    <a href="function/deconnexion.php">deco</a>
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