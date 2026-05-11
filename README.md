# Module Connexion — Documentation

## Résumé

Module PHP minimal pour gérer : inscription, connexion, déconnexion et profil utilisateur.

## Objectif

Fournir un module simple et réutilisable pour apprendre ou démarrer rapidement une gestion d'utilisateurs en PHP.

## Table des matières

- [Prérequis](#prérequis)
- [Installation rapide](#installation-rapide)
- [Configuration de la base de données](#configuration-de-la-base-de-donn%C3%A9es)
- [Arborescence et pages principales](#arborescence-et-pages-principales)
- [Fonctionnalités détaillées](#fonctionnalit%C3%A9s-d%C3%A9taill%C3%A9es)
- [Sécurité et bonnes pratiques](#s%C3%A9curit%C3%A9-et-bonnes-pratiques)
- [Dépannage](#d%C3%A9pannage)
- [Contribuer / Contact](#contribuer--contact)

## Prérequis

- PHP 7.4+ (ou version compatible)
- Serveur web local (par ex. Laragon, XAMPP, WAMP)
- MySQL / MariaDB

## Installation rapide

1. Copier le répertoire du projet dans le dossier web (ex. `www` pour Laragon).
2. Importer la base de données fournie :
   - Ouvrir phpMyAdmin ou un client MySQL
   - Créer une base (par exemple `moduleconnexion`) puis importer `moduleconnexion.sql`
3. Configurer la connexion à la DB dans [structure/db.php](structure/db.php).

Commande d'import MySQL (optionnel) :

```bash
mysql -u root -p moduleconnexion < moduleconnexion.sql
```

## Configuration de la base de données

Ouvrez [structure/db.php](structure/db.php) et renseignez vos paramètres :

```php
<?php
$host = '127.0.0.1';
$dbname = 'moduleconnexion';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
```

## Arborescence et pages principales

- [index.php](index.php) — point d'entrée
- [pages/inscription.php](pages/inscription.php) — formulaire d'inscription
- [pages/connexion.php](pages/connexion.php) — formulaire de connexion
- [pages/deconnexion.php](pages/deconnexion.php) — action de déconnexion
- [pages/profil.php](pages/profil.php) — page de profil utilisateur
- [structure/header.php](structure/header.php) / [structure/footer.php](structure/footer.php) — fragments d'UI
- [moduleconnexion.sql](moduleconnexion.sql) — script SQL pour créer les tables

## Fonctionnalités détaillées

- Inscription : enregistrement d'un nouvel utilisateur (vérification des champs, unicité de l'email).
- Connexion : authentification via email et mot de passe.
- Déconnexion : destruction de la session utilisateur.
- Profil : affichage et modification des informations de l'utilisateur.

## Sécurité et bonnes pratiques

- Toujours utiliser `password_hash()` pour stocker les mots de passe et `password_verify()` pour vérifier.
- Valider et assainir toutes les entrées (filtrer les emails, limiter les longueurs).
- Utiliser des requêtes préparées (PDO + requêtes préparées) pour éviter les injections SQL.
- Implémenter des protections CSRF pour les formulaires (token dans la session).
- Utiliser HTTPS en production.

Exemple de hachage de mot de passe :

```php
$hash = password_hash($password, PASSWORD_DEFAULT);
if (password_verify($password_candidate, $hash)) {
    // authentifié
}
```

## Dépannage

- Erreur de connexion DB : vérifier [structure/db.php](structure/db.php) et que la base `moduleconnexion` a été importée.
- Erreurs PHP : activer l'affichage des erreurs en environnement de développement :

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## Contribuer / Contact

Si vous souhaitez que j'ajoute :

- Un guide d'installation détaillé pour Laragon (pas à pas)
- Exemples de formulaires complets pour `pages/inscription.php` et `pages/connexion.php`
- Tests unitaires ou scripts de vérification

Ouvrez une issue ou contactez-moi ici et je peux ajouter ces sections.

## Licence

Ce projet est fourni tel quel — ajoutez une licence si vous le souhaitez.
