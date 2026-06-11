<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./ressources/includes/connexion-bdd.php');

// Vérification de la connexion à la base de données
if (!$mysqli_link) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$couleur_bulle_classe = "vert";
$page_active = "equipederedaction";

// Exécution de la requête SQL
$requete_brute = "SELECT * FROM auteur";
$resultat_brut = mysqli_query($mysqli_link, $requete_brute);

// Vérification du résultat de la requête
if (!$resultat_brut) {
    die("Erreur dans la requête SQL : " . mysqli_error($mysqli_link));
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <base href="/<?php echo $_ENV['CHEMIN_BASE']; ?>">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe de rédaction - SAÉ 203</title>

    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/reset.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/fonts.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/global.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/header.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/accueil.css">
    <link rel="stylesheet" href="./ressources/css/equipe.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <?php require_once('./ressources/includes/top-navigation.php'); ?>
    <?php require_once('./ressources/includes/bulle.php'); ?>

    <main class="conteneur-principal conteneur-1280">

        <?php if (!$resultat_brut || mysqli_num_rows($resultat_brut) === 0) { ?>
            <p>Aucun résultat trouvé.</p>
        <?php } else { ?>

            <!-- GRID DES CARTES -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">

                <?php while ($element = mysqli_fetch_array($resultat_brut, MYSQLI_ASSOC)) { ?>

                    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                        
                        <img class="w-full h-48 object-cover"
                             src="<?php echo htmlentities($element['lien_avatar'], ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo htmlentities('Portrait '.$element['prenom'], ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">
                                <?php echo htmlentities($element['prenom'].' '.$element['nom'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <p class="text-gray-700 text-base break-alltransform transition hover:-translate-y-1 motion-reduce:transition-none motion-reduce:hover:transform-none ">
                
                                <a href="<?php echo htmlentities($element['lien_twitter'], ENT_QUOTES, 'UTF-8'); ?>"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900">
                                    <?php echo htmlentities($element['lien_twitter'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </p>
                        </div>

                        <div class="px-6 pt-4 pb-2">
                            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                #Rédacteur
                            </span>
                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } ?>

    </main>

    <?php require_once('./ressources/includes/footer.php'); ?>
</body>

</html>
