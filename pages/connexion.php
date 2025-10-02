<?php
session_start(); // Session AVANT tout output HTML

// Fonction de vérification utilisateur avec mots de passe en clair
function getUser($login, $password)
{
    require_once '../structure/db.php';
    $connection = connect_pdo();

    // Récupérer l'utilisateur par login et password directement
    $req = $connection->prepare("SELECT * FROM utilisateurs WHERE login = ? AND Password = ?");
    $req->execute([$login, $password]);

    if ($req->rowCount() == 1) {
        return $req->fetch();
    } elseif ($req->rowCount() == 0) {
        // Vérifier si le login existe pour donner un message d'erreur précis
        $check_login = $connection->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $check_login->execute([$login]);

        if ($check_login->rowCount() > 0) {
            return ['error' => 'wrong_password']; // Login existe mais mot de passe incorrect
        } else {
            return ['error' => 'login_not_found']; // Login n'existe pas
        }
    } else {
        return ['error' => 'duplicate_login']; // Plusieurs utilisateurs avec le même login
    }
}

// Traitement du formulaire
$error_message = '';
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);

    // Vérification de l'utilisateur
    $user = getUser($login, $password);

    if ($user && !isset($user['error'])) {
        // Connexion réussie - créer les variables de session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];

        // Stocker le rôle (si la colonne existe, sinon par défaut 'user')
        $_SESSION['role'] = $user['login'] == "admin" ? "admin" : 'user';

        // Redirection selon le rôle
        if ($user['login'] == 'admin') {
            header("location:admin.php");
        } else {
            header("location:profil.php");
        }
        exit(); // Arrêter l'exécution après redirection
    } else {
        // Gestion des erreurs spécifiques
        if (isset($user['error'])) {
            switch ($user['error']) {
                case 'login_not_found':
                    $error_message = "Ce nom d'utilisateur n'existe pas !";
                    break;
                case 'wrong_password':
                    $error_message = "Mot de passe incorrect pour cet utilisateur !";
                    break;
                case 'duplicate_login':
                    $error_message = "Erreur de base de données : plusieurs utilisateurs avec le même login !";
                    break;
                case 'no_password':
                    $error_message = "Erreur : aucun mot de passe configuré pour cet utilisateur !";
                    break;
                default:
                    $error_message = "Erreur de connexion inconnue !";
            }
        } else {
            $error_message = "Login ou mot de passe incorrect!";
        }
    }
}

include '../structure/header.php'; // Header APRÈS le traitement PHP
?>

<main>
    <!-- Une page contenant un formulaire de connexion (connexion.php) : OK
Le formulaire doit avoir deux inputs : “login” et “password”.    OK
Lorsque le formulaire est validé, s’il existe un utilisateur en base de données correspondant à ces informations, alors
l’utilisateur est considéré comme connecté et une (ou plusieurs) variables de session sont créées. -->

    <form class="formulaire_connexion" action="" method="post">
        <h1 class="titreh1">
            <img src="../assets/img/logo.png" alt="Logo" class="logo-titre">
            Se connecter
        </h1>

        <?php if (!empty($error_message)): ?>
            <div style="color: red; text-align: center; margin: 15px 0; font-weight: bold;background-color:white" ;>
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <label for="login">Login:</label>
        <input placeholder="Nom utilisateur" type="text" id="login" name="login" required>

        <label for="password">Mot de passe:</label>
        <input placeholder="Mot de passe" type="password" id="password" name="password" required>

        <button>Connexion</button>

    </form>



</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>