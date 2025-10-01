<?php
// CREATION DE LA CONNEXION A LA BASE DE DONNEES

function connect_pdo()
{
    try {
        // Ici tu crées une connexion PDO à la base MySQL "jour09"
        // - host=localhost : ton serveur est en local
        // - dbname=jour09 : c’est le nom de ta base de données
        // - "root" : ton identifiant (à éviter en production)
        // - "" : contient le mot de passe
        $pdo = new PDO("mysql:host=localhost;dbname=moduleconnexion", "root", "");

        // Tu configures PDO pour qu’il envoie une exception si une erreur SQL survient
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Si une erreur se produit (connexion ou requête), tu récupères le message d’erreur
        echo "Erreur : " . $e->getMessage();
    }
}
