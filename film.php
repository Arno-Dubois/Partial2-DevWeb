<link rel="stylesheet" href="style.css">

<?php
ini_set('display_errors', 1);
// Afficher les erreurs et les avertissements
error_reporting(E_ALL);

if (isset($_GET["id"])) {
    require_once ("pdo.php");
    date_default_timezone_set('Europe/Paris');
    $month = ["janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre"];

    $resultat = $dbPDO->prepare("   SELECT titre, `date de sortie` AS `date`, durée, libellé, prénom, nom, `description`, realisateur.id AS filmmakerId FROM `film` INNER JOIN genre ON genre = genre.id INNER JOIN realisateur ON réalisateur = realisateur.id WHERE film.id = :id");
    $resultat->execute([
        "id" => $_GET["id"]
    ]) or die(print_r($resultat->errorInfo()));

    $rows = $resultat->rowCount();
    if ($rows > 0) {
        $film = $resultat->fetch();
        $release_date = DateTime::createFromFormat("Y-m-d", $film["date"]);
        $hours = floor($film["durée"] / 60);
        $minutes = ($film["durée"] % 60);
        echo '<h1 style="font-family: Jersey">' . $film["titre"] . ' :</h1><div style="font-family: Raleway">' . $release_date->format("j ") . $month[$release_date->format("n")] . $release_date->format(" Y") . " en salle | " . $hours . "h " . $minutes . "min | " . $film["libellé"] . '<br><br>De <a href="/Partial2-DevWeb/filmmaker.php/id=' . $film["filmmakerId"] . '">' . $film["prénom"] . ' ' . $film["nom"] . '</a><br><br><b>Synopsis : </b>' . $film["description"] . '</div>';

    } else {
        header("Location: /Partial2-DevWeb/");
    }

} else {
    header("Location: /Partial2-DevWeb/");
}
