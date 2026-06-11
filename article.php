<?php
$couleur_bulle_classe = "rose";
$page_active = "index";
function decode_array($array) {
    foreach ($array as $key => $value) {
        if (is_string($value)) {
            $array[$key] = html_entity_decode($value);
        }
    }
    return $array;
}
require_once('./ressources/includes/connexion-bdd.php');

// Sécurisation de l'ID via GET
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

// Requête SQL avec jointure auteur
$requete_brute = "
    SELECT ar.*, CONCAT(a.nom, ' ', a.prenom) AS auteur_nom
    FROM article ar
    LEFT JOIN auteur a ON ar.auteur_id = a.id
    WHERE ar.id = $id
";
$resultat_brut = mysqli_query($mysqli_link, $requete_brute);
$entite = mysqli_fetch_array($resultat_brut, MYSQLI_ASSOC);
$entite = decode_array($entite);


// Sécurité : si article introuvable
if (!$entite) {
    http_response_code(404);
    echo "<h1>Article introuvable</h1>";
    exit;
}

$date_creation = new DateTime($entite["date_creation"]);
$auteur = !empty($entite["auteur_nom"]) ? $entite["auteur_nom"] : "Auteur inconnu";
$image = !empty($entite["image"]) ? $entite["image"] : "ressources/images/image-article.png";
$video = !empty($entite["lien_yt"]) ? $entite["lien_yt"] : null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <base href="/<?php echo $_ENV['CHEMIN_BASE']; ?>">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($entite["titre"]); ?> - SAÉ 203</title>

    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/reset.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/fonts.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/global.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/header.css">
    <link rel="stylesheet" href="./ressources/css/ne-pas-modifier/accueil.css">
    <link rel="stylesheet" href="./ressources/css/accueil.css">
    <link rel="stylesheet" href="./ressources/css/page-article.css">
</head>

<body>
    <?php require_once('./ressources/includes/top-navigation.php'); ?>
    <?php require_once('./ressources/includes/bulle.php'); ?>

    <main class="conteneur-principal conteneur-1280 article-detail">
        <h1 class="titre"><?php echo htmlspecialchars($entite["titre"]); ?></h1>

            <article class="contenu-article">
                <p class="chapo"><?php echo nl2br(htmlspecialchars($entite["chapo"])); ?></p>
                <p class="contenu"><?php echo nl2br(htmlspecialchars($entite["contenu"])); ?></p>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Image de l'article" class="image-article">

            </article>

            <p class="date">
                Publié le 
                <time datetime="<?php echo $date_creation->format('Y-m-d H:i:s'); ?>">
                    <?php echo $date_creation->format('d/m/Y à H:i:s'); ?>
                </time>
            </p>

            <p class="auteur">Par : <?php echo htmlspecialchars($auteur); ?></p>


            <?php
                function youtube_embed_url($url) {
                    // Si déjà au format embed
                    if (strpos($url, 'embed') !== false) return $url;
                    // Lien classique
                    if (preg_match('/v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                        return 'https://www.youtube.com/embed/' . $matches[1];
                    }
                    // Lien youtu.be
                    if (preg_match('#youtu\.be/([a-zA-Z0-9_-]+)#', $url, $matches)) {
                        return 'https://www.youtube.com/embed/' . $matches[1];
                    }
                    // Juste l'ID
                    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
                        return 'https://www.youtube.com/embed/' . $url;
                    }
                    return '';
                }
            ?>
            <?php if ($video): ?>
                <div class="video mt-6">
                    <iframe class="w-full aspect-video" src="<?php echo htmlspecialchars(youtube_embed_url($video)); ?>" title="Vidéo YouTube" frameborder="0" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
    </main>

    <?php require_once('./ressources/includes/footer.php'); ?>
</body>
</html>
