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

$success_message = '';
$error_message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom'])) {

    // Nettoyer les données d'entrée
    $login = trim($_POST['login']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);

    // Validation des champs obligatoires
    if (empty($login) || empty($nom) || empty($prenom)) {
        $error_message = "Tous les champs (login, nom, prénom) sont obligatoires.";
    }

    // Vérifier si le login n'est pas déjà utilisé par un autre utilisateur
    elseif ($login !== $user['login']) {
        $check_stmt = $connection->prepare("SELECT id FROM utilisateurs WHERE login = ? AND id != ?");
        $check_stmt->execute([$login, $_SESSION['user_id']]);
        if ($check_stmt->rowCount() > 0) {
            $error_message = "Ce login est déjà utilisé par un autre utilisateur.";
        }
    }

    // Vérification des mots de passe si fournis
    if (empty($error_message) && (!empty($_POST['password']) || !empty($_POST['confirm_password']))) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $error_message = "Les mots de passe ne correspondent pas.";
        } elseif (strlen($_POST['password']) < 6) {
            $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
        }
    }

    // Si pas d'erreur, procéder à la mise à jour
    if (empty($error_message)) {
        try {
            // Si un nouveau mot de passe est fourni
            if (!empty($_POST['password'])) {
                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE utilisateurs SET login = ?, nom = ?, prenom = ?, password = ? WHERE id = ?";
                $profil = $connection->prepare($sql);
                $profil->execute([
                    $login,
                    $nom,
                    $prenom,
                    $hashed_password,
                    $_SESSION['user_id']
                ]);
            } else {
                // Mise à jour sans le mot de passe
                $sql = "UPDATE utilisateurs SET login = ?, nom = ?, prenom = ? WHERE id = ?";
                $profil = $connection->prepare($sql);
                $profil->execute([
                    $login,
                    $nom,
                    $prenom,
                    $_SESSION['user_id']
                ]);
            }

            // Mettre à jour les variables de session
            $_SESSION['login'] = $login;
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;

            // Recharger les données utilisateur pour le formulaire
            $stmt = $connection->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            $success_message = "Profil mis à jour avec succès !";
        } catch (PDOException $e) {
            $error_message = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}

include '../structure/header.php'; // Inclut le header
?>

<main>
    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

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

        <input type="submit" value="Valider les modifications">

    </form>
</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>