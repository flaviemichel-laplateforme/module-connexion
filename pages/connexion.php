<?php
include '../structure/header.php'; // Inclut le header
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