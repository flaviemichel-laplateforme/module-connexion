<?php
session_start();
include '../structure/header.php'; // Inclut le header
include '../structure/db.php'; // Inclut la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("location:connexion.php");
    exit();
}

// Vérifier si l'utilisateur a le rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<main><div class='error-message'>Accès refusé. Cette page est réservée aux administrateurs.</div></main>";
    include '../structure/footer.php';
    exit();
}
?>

<main>
    <!-- Une page d’administration (admin.php) :
Cette page est accessible UNIQUEMENT pour l’utilisateur “admin”. Elle permet
de lister l’ensemble des informations des utilisateurs présents dans la base de
données. -->
</main>

<?php
include '../structure/footer.php'; // Inclut le footer
?>