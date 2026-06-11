<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('./ressources/includes/connexion-bdd.php');

// CORRECTION : On utilise EXACTEMENT le nom attendu par top-navigation.php
$page_active = "connexion"; 

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = mysqli_real_escape_string($mysqli_link, $_POST['identifiant']);
    $password_hache = md5($_POST['mot_de_passe']);

    $requete = "SELECT * FROM utilisateur WHERE identifiant = '$identifiant' AND mot_de_passe = '$password_hache'";
    $resultat = mysqli_query($mysqli_link, $requete);
    $user = mysqli_fetch_assoc($resultat);

    if ($user) {
        $_SESSION['admin_connecte'] = true;
        header("Location: ./administration/auteurs/index.php"); 
        exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <base href="/<?php echo $_ENV['CHEMIN_BASE']; ?>">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - SAÉ 203</title>

    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/reset.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/fonts.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/global.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/header.css">
    
    <link rel="stylesheet" href="./ressources/css/navigation.css"> 
</head>

<body>
    <?php require_once('./ressources/includes/top-navigation.php'); ?>

    <main style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 80px); background-color: #f9fafb; padding: 2rem;">
        
        <div style="background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); width: 100%; max-width: 400px; border: 1px solid #e5e7eb;">
            
            <h1 style="font-size: 1.8rem; font-weight: bold; text-align: center; margin-bottom: 1.5rem; color: #111827;">
                Connexion
            </h1>
            
            <?php if (!empty($erreur)): ?>
                <p style="color: #dc2626; background-color: #fee2e2; padding: 0.75rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid #fca5a5; text-align: center;">
                    <?php echo $erreur; ?>
                </p>
            <?php endif; ?>

            <form method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Identifiant</label>
                    <input type="text" name="identifiant" style="width: 100%; border: 1px solid #d1d5db; padding: 0.75rem; border-radius: 6px; font-size: 1rem; outline: none;" required>
                </div>
                
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Mot de passe</label>
                    <input type="password" name="mot_de_passe" style="width: 100%; border: 1px solid #d1d5db; padding: 0.75rem; border-radius: 6px; font-size: 1rem; outline: none;" required>
                </div>
                
                <button type="submit" style="width: 100%; background-color: #111827; color: white; padding: 0.75rem; border-radius: 6px; border: none; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 0.5rem;">
                    Se connecter
                </button>
                
            </form>
        </div>
    </main>

    </body>

</html>