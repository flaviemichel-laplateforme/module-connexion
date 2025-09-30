<?php
include '../structure/header.php'; // Inclut le header
?>

<main>



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

        <button>S'inscrire</button>

    </form>

</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>