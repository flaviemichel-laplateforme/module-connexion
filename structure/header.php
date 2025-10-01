<?php
// Déterminer le chemin racine selon l'emplacement du fichier qui inclut ce header
$currentDir = dirname($_SERVER['PHP_SELF']);
$isInSubfolder = (strpos($currentDir, '/pages') !== false);

// Définir le chemin de base selon si on est dans un sous-dossier ou à la racine
$basePath = $isInSubfolder ? '../' : './';

// Chemins vers les ressources
$cssPath = $basePath . 'assets/css/style.css?v=' . time(); // Force le rechargement
$logoPath = $basePath . 'assets/img/logo.png';
$indexPath = $isInSubfolder ? '../index.php' : 'index.php';
$pagesPath = $isInSubfolder ? './' : 'pages/';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Connexion</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>">
</head>

<body>
    <header class="nav-menu">
        <div>
            <img src="<?= $logoPath; ?>" alt="Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="<?= $indexPath; ?>">Accueil</a></li>
                <li><a href="<?= $pagesPath; ?>connexion.php">Connexion</a></li>
                <li><a href="<?= $pagesPath; ?>inscription.php">Inscription</a></li>
                <li><a href="<?= $pagesPath; ?>profil.php">Profil</a></li>
                <li><a href="<?= $pagesPath; ?>admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    <div class="container"> <!-- Container principal -->