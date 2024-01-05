<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "project";

// Crée une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error. "\n");
}

echo "Connexion réussie !\n";

?>
