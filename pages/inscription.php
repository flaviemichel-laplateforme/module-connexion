<?php
include '../structure/header.php'; // Inclut le header

if (isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['password'])) {
    require_once '../structure/db.php';
    $connection = connect_pdo();

    $sql = "INSERT INTO utilisateurs(login,nom,prenom,password) VALUE (?,?,?,?)";

    $inscription = $connection->prepare($sql);
    $inscription->execute([$_POST['login'], $_POST['nom'], $_POST['prenom'], $_POST['password']]);

    header("location:connexion.php");
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