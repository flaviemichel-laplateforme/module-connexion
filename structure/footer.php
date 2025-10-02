<?php
// Utiliser la même logique de chemins que dans le header
$currentDir = dirname($_SERVER['PHP_SELF']);
$isInSubfolder = (strpos($currentDir, '/pages') !== false);
$basePath = $isInSubfolder ? '../' : './';
?>

<footer>
    <div class="container">
        <a href="https://github.com/flaviemichel-laplateforme">
            <img src="<?= $basePath; ?>assets/img/github.jpg" alt="GitHub">
        </a>
    </div>

    <div>
        <p>Merci de votre visite et à très bientôt sur notre site !
            Votre satisfaction est notre priorité.
            © 2025 – Tous droits réservés.</p>
    </div>

    <div class="container2">
        <a href="https://www.linkedin.com/in/flavie-michel-a112a41b7/">
            <img src="<?= $basePath; ?>assets/img/linkedin.png" alt="LinkedIn">
        </a>
    </div>

</footer>
</body>

</html>