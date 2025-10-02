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
    <div class="admin-container">
        <h1>Page d'Administration</h1>
        <h2>Liste des utilisateurs</h2>

        <?php
        try {
            // Utiliser la fonction de connexion existante
            $connection = connect_pdo();

            // Requête pour récupérer tous les utilisateurs
            $sql = "SELECT id, login, nom, prenom, Password FROM utilisateurs ORDER BY id ASC";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) > 0) {
        ?>
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Login</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Mot de passe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['login']); ?></td>
                                <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                <td><?php echo htmlspecialchars($user['Password']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="total-count">
                    <strong>Total : <?php echo count($users); ?> utilisateur(s) trouvé(s)</strong>
                </p>
        <?php
            } else {
                echo '<div class="no-data">';
                echo '<p>Aucun utilisateur trouvé dans la base de données.</p>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="error-message">';
            echo '<strong>Erreur de base de données :</strong> ' . htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
    </div>
</main>

<!-- Une page d’administration (admin.php) :
Cette page est accessible UNIQUEMENT pour l’utilisateur “admin”. Elle permet
de lister l’ensemble des informations des utilisateurs présents dans la base de
données. -->


<?php
include '../structure/footer.php'; // Inclut le footer
?>