<?php
echo "<h2>🔧 Diagnostic et correction admin</h2>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.error { color: red; font-weight: bold; }
.success { color: green; font-weight: bold; }
.warning { color: orange; font-weight: bold; }
.info { color: blue; }
button { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
.btn-danger { background: #f44336; color: white; }
.btn-success { background: #4CAF50; color: white; }
.btn-warning { background: #ff9800; color: white; }
</style>";

try {
    require_once '../structure/db.php';
    $connection = connect_pdo();
    echo "<p class='success'>✅ Connexion à la base réussie</p>";

    // Vérifier la structure de la table
    echo "<h3>📋 Colonnes de la table utilisateurs :</h3>";
    $structure = $connection->query("DESCRIBE utilisateurs");
    $columns = $structure->fetchAll(PDO::FETCH_ASSOC);

    $hasPasswordColumn = false;
    $passwordColumnName = '';

    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
        if (strtolower($col['Field']) === 'password') {
            $hasPasswordColumn = true;
            $passwordColumnName = $col['Field'];
        }
    }

    if (!$hasPasswordColumn) {
        echo "<p class='error'>❌ Aucune colonne 'password' trouvée !</p>";
        // Chercher d'autres colonnes possibles
        foreach ($columns as $col) {
            if (stripos($col['Field'], 'pass') !== false || stripos($col['Field'], 'pwd') !== false) {
                echo "<p class='warning'>⚠️ Colonne similaire trouvée : " . $col['Field'] . "</p>";
            }
        }
    } else {
        echo "<p class='success'>✅ Colonne password trouvée : " . $passwordColumnName . "</p>";
    }

    // Vérifier l'utilisateur admin
    echo "<h3>🔍 Vérification de l'utilisateur admin :</h3>";
    $adminReq = $connection->prepare("SELECT * FROM utilisateurs WHERE login = 'admin'");
    $adminReq->execute();

    if ($adminReq->rowCount() == 0) {
        echo "<p class='error'>❌ Utilisateur admin non trouvé !</p>";
        echo "<form method='post'>";
        echo "<button type='submit' name='create_admin' class='btn-success'>Créer l'utilisateur admin</button>";
        echo "</form>";
    } else {
        $admin = $adminReq->fetch();
        echo "<p class='success'>✅ Utilisateur admin trouvé</p>";

        // Afficher toutes les colonnes
        echo "<ul>";
        foreach ($admin as $key => $value) {
            if (!is_numeric($key)) { // Skip numeric indices
                $displayValue = ($value === null) ? 'NULL' : htmlspecialchars($value);
                $class = ($value === null) ? 'error' : 'info';
                echo "<li><strong>" . htmlspecialchars($key) . " :</strong> <span class='$class'>" . $displayValue . "</span></li>";
            }
        }
        echo "</ul>";

        // Vérifier spécifiquement les colonnes de mot de passe possibles
        $passwordValue = null;
        $actualPasswordColumn = null;

        if (isset($admin['Password'])) {
            $passwordValue = $admin['Password'];
            $actualPasswordColumn = 'Password';
        } elseif (isset($admin['password'])) {
            $passwordValue = $admin['password'];
            $actualPasswordColumn = 'password';
        }

        if ($passwordValue === null) {
            echo "<p class='error'>❌ Le mot de passe est NULL dans la colonne " . ($actualPasswordColumn ?: 'introuvable') . "</p>";
            echo "<form method='post'>";
            echo "<button type='submit' name='fix_admin_password' class='btn-warning'>Corriger le mot de passe admin</button>";
            echo "</form>";
        } else {
            echo "<p class='success'>✅ Mot de passe trouvé dans la colonne '$actualPasswordColumn' : " . htmlspecialchars($passwordValue) . "</p>";

            // Test de connexion
            echo "<h4>🧪 Test de connexion :</h4>";
            if (password_verify('admin', $passwordValue)) {
                echo "<p class='success'>✅ password_verify('admin') : SUCCÈS</p>";
            } else {
                echo "<p class='warning'>⚠️ password_verify('admin') : ÉCHEC</p>";
            }

            if ('admin' === $passwordValue) {
                echo "<p class='success'>✅ Comparaison directe : SUCCÈS</p>";
                echo "<p class='success'>🎉 La connexion devrait fonctionner !</p>";
            } else {
                echo "<p class='warning'>⚠️ Comparaison directe : ÉCHEC</p>";
                echo "<p>Le mot de passe stocké est : '" . htmlspecialchars($passwordValue) . "'</p>";
            }
        }
    }

    // Traitement des formulaires
    if (isset($_POST['create_admin'])) {
        echo "<h3>🔧 Création de l'utilisateur admin :</h3>";
        try {
            $stmt = $connection->prepare("INSERT INTO utilisateurs (login, nom, prenom, Password) VALUES ('admin', 'admin', 'admin', 'admin')");
            $stmt->execute();
            echo "<p class='success'>✅ Utilisateur admin créé !</p>";
            echo "<p><a href='diagnostic.php'>🔄 Actualiser</a></p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    if (isset($_POST['fix_admin_password'])) {
        echo "<h3>🔧 Correction du mot de passe admin :</h3>";
        try {
            $stmt = $connection->prepare("UPDATE utilisateurs SET Password = 'admin' WHERE login = 'admin'");
            $stmt->execute();
            echo "<p class='success'>✅ Mot de passe admin corrigé !</p>";
            echo "<p><a href='diagnostic.php'>🔄 Actualiser</a></p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<div style="margin-top: 30px; padding: 15px; background-color: #f0f0f0; border-radius: 5px;">
    <h3>🔗 Navigation</h3>
    <a href="connexion.php" style="background: #2196F3; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px; margin-right: 10px;">← Tester la connexion</a>
    <a href="../index.php" style="background: #4CAF50; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px;">🏠 Accueil</a>
</div>