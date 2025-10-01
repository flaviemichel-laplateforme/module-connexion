<?php
session_start(); // Session AVANT tout output HTML

// Fonction de vérification utilisateur
function getUser($login, $password)
{
    require_once '../structure/db.php';
    $connection = connect_pdo();

    $req = $connection->prepare("SELECT * FROM utilisateurs WHERE login = ? AND password = ?");
    $req->execute([$login, $password]);

    if ($req->rowCount() == 1) {
        return $req->fetch();
    } else {
        return false;
    }
}

// Traitement du formulaire
$error_message = '';
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);

    // Vérification de l'utilisateur
    $user = getUser($login, $password);

    if ($user) {
        // Créer les variables de session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];

        // Redirection correcte vers admin.php (dans le même dossier pages/)
        header("location:admin.php");
        exit(); // Arrêter l'exécution après redirection
    } else {
        $error_message = "Login ou mot de passe incorrect!";
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