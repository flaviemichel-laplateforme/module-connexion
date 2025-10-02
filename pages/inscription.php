<?php
session_start(); // Démarrer la session
include '../structure/header.php'; // Inclut le header

$error_message = '';
$success_message = '';

if (isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {

    // Sécuriser et nettoyer les données d'entrée
    $login = trim(htmlspecialchars($_POST['login']));
    $nom = trim(htmlspecialchars($_POST['nom']));
    $prenom = trim(htmlspecialchars($_POST['prenom']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation des données
    if (empty($login) || empty($nom) || empty($prenom) || empty($password)) {
        $error_message = "Tous les champs sont obligatoires !";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas !";
    } elseif (strlen($password) < 4) {
        $error_message = "Le mot de passe doit contenir au moins 4 caractères !";
    } else {
        try {
            require_once '../structure/db.php';
            $connection = connect_pdo();

            // Vérifier si le login existe déjà
            $check_sql = "SELECT id FROM utilisateurs WHERE login = ?";
            $check_stmt = $connection->prepare($check_sql);
            $check_stmt->execute([$login]);

            if ($check_stmt->rowCount() > 0) {
                $error_message = "Ce nom d'utilisateur existe déjà ! Choisissez un autre login.";
            } else {
                // Insérer le nouvel utilisateur (correction de la syntaxe SQL)
                $sql = "INSERT INTO utilisateurs(login, nom, prenom, Password) VALUES (?, ?, ?, ?)";
                $inscription = $connection->prepare($sql);
                $inscription->execute([$login, $nom, $prenom, $password]);

                $success_message = "Inscription réussie ! Redirection vers la page de connexion...";
                // Redirection après 2 secondes pour laisser le temps de voir le message
                header("refresh:2;url=connexion.php");
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Code d'erreur pour violation de contrainte d'intégrité
                $error_message = "Ce nom d'utilisateur est déjà utilisé !";
            } else {
                $error_message = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
}

?>



<main>
    <!-- Une page contenant un formulaire d’inscription (inscription.php) :
Le formulaire doit contenir l’ensemble des champs présents dans la table
“utilisateurs” (sauf “id”) + une confirmation de mot de passe. 
Dès qu’un
utilisateur remplit ce formulaire, les données sont insérées dans la base de
données et l’utilisateur est redirigé vers la page de connexion. -->


    <form class="formulaire_inscription" action="" method="post">
        <h1 class="titreh1">
            <img src="../assets/img/logo.png" alt="Logo" class="logo-titre">
            S'inscrire ici
        </h1>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <label for="login">Login:</label>
        <input placeholder="Choisir un nom utilisateur" type="text" id="login" name="login" required>

        <label for="prenom">Prénom:</label>
        <input placeholder="Prénom" type="text" id="prenom" name="prenom" required>

        <label for="nom">Nom:</label>
        <input placeholder="Nom" type="text" id="nom" name="nom" required>

        <label for="password">Mot de passe:</label>
        <input placeholder="Mot de passe" type="password" id="password" name="password" required>

        <label for="confirm_password">Confirmez le mot de passe:</label>
        <input placeholder="Confirmez le mot de passe" type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" value="S'inscrire">

    </form>

</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>