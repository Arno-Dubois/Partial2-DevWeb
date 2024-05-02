<?php
ini_set('display_errors', 1);
// Afficher les erreurs et les avertissements
error_reporting(E_ALL);
ob_start();

require_once ("pdo.php");

$resultat = $dbPDO->prepare("   SELECT titre, genre.libellé, nom, prénom, `date de sortie` AS date
                                FROM film
                                INNER JOIN genre ON genre = genre.id
                                INNER JOIN realisateur ON réalisateur = realisateur.id");
$resultat->execute() or die(print_r($resultat->errorInfo()));
$top_films = $resultat->fetchAll(PDO::FETCH_CLASS);

echo '<h1>Liste des meilleurs films des années 2010</h1><ul>';

foreach ($top_films as $film) {
    $release_year = DateTime::createFromFormat("Y-m-d", $film->date);
    echo '<li><a href="https://allocine.fr/rechercher/?q=' . $film->titre . '">' . $film->titre . '</a> (' . $film->libellé . ' de <a href="https://allocine.fr/rechercher/person/?q=' . $film->prénom . ' ' . $film->nom . '">' . $film->prénom . ' ' . $film->nom . "</a>, " . $release_year->format("Y") . ')</li>';
}

echo '</ul>';