<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("location:connexion.php");
    exit();
}

// Récupérer les données de l'utilisateur depuis la base
require_once '../structure/db.php';
$connection = connect_pdo();

$stmt = $connection->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Si l'utilisateur n'existe pas en base
if (!$user) {
    session_destroy();
    header("location:connexion.php");
    exit();
}

include '../structure/header.php'; // Inclut le header
?>

<main>
    <!-- Une page permettant de modifier son profil (profil.php) :
Cette page possède un formulaire permettant à l’utilisateur de modifier ses
informations. 
Ce formulaire est par défaut pré-rempli avec les informations qui
sont actuellement stockées en base de données. -->

    <form class="formulaire_modification_profil" action="" method="post">
        <h1 class="titreh1">
            <img src="../assets/img/logo.png" alt="Logo" class="logo-titre">
            Modifier son profil
        </h1>



        <label for="login">Login:</label>
        <input type="text" id="login" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

        <label for="password">Nouveau mot de passe:</label>
        <input placeholder="Laissez vide pour conserver l'actuel" type="password" id="password" name="password">

        <label for="confirm_password">Confirmez le nouveau mot de passe:</label>
        <input placeholder="Confirmez le mot de passe" type="password" id="confirm_password" name="confirm_password">

        <button>Valider les modifications</button>

    </form>
</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>