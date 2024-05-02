<link rel="stylesheet" href="style.css">

<?php
ini_set('display_errors', 1);
// Afficher les erreurs et les avertissements
error_reporting(E_ALL);

require_once ("pdo.php");
date_default_timezone_set('Europe/Paris');

$resultat = $dbPDO->prepare("   SELECT titre, libellé, nom, prénom, `date de sortie` AS date, film.id, realisateur.id AS filmmaker
                                FROM film
                                INNER JOIN genre ON genre = genre.id
                                INNER JOIN realisateur ON réalisateur = realisateur.id");
$resultat->execute() or die(print_r($resultat->errorInfo()));
$top_films = $resultat->fetchAll(PDO::FETCH_CLASS);

echo '<h1 style="font-family: Jersey">Liste des meilleurs films des années 2010</h1><ul style="font-family: Raleway">';

foreach ($top_films as $film) {
    $release_date = DateTime::createFromFormat("Y-m-d", $film->date);
    echo '<li><a href="/Partial2-DevWeb/film.php/?id=' . $film->id . '">' . $film->titre . '</a> (' . $film->libellé . ' de <a href="/Partial2-DevWeb/filmmaker.php/?id=' . $film->filmmaker . '">' . $film->prénom . ' ' . $film->nom . "</a>, " . $release_date->format("Y") . ')</li>';
}

echo '</ul>';