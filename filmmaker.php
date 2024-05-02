<link rel="stylesheet" href="style.css">

<?php
ini_set('display_errors', 1);
// Afficher les erreurs et les avertissements
error_reporting(E_ALL);

if (isset($_GET["id"])) {
    require_once ("pdo.php");
    date_default_timezone_set('Europe/Paris');

    $resultat = $dbPDO->prepare("   SELECT prénom, nom, nationalité
                                    FROM realisateur");
    $resultat->execute() or die(print_r($resultat->errorInfo()));

    $rows = $resultat->rowCount();
    if ($rows > 0) {
        $filmmaker = $resultat->fetch();

        echo '<h1 style="font-family: Jersey">' . $filmmaker["prénom"] . ' ' . $filmmaker["nom"] . ' :</h1><div style="font-family: Raleway"><b>Nationalité : </b>' . $filmmaker["nationalité"] . "<br><br><b>Filmographie : </b>";

        $resultat = $dbPDO->prepare("   SELECT titre, `date de sortie` AS `date`, realisateur.id AS 'real'
                                        FROM film WHERE réalisateur = :id");
        $resultat->execute([
            "id" => $_GET["id"]
        ]) or die(print_r($resultat->errorInfo()));

        $films = $resultat->fetchAll(PDO::FETCH_CLASS);
        foreach ($films as $film) {
            $release_date = DateTime::createFromFormat("Y-m-d", $film->date);
            echo '<li><a href="/Partial2-DevWeb/film.php/?id=' . $film->real . '">' . $film->titre . '</a>,' . $release_date->format("Y") . '</li>';
        }

    } else {
        header("Location: /Partial2-DevWeb/");
    }

} else {
    header("Location: /Partial2-DevWeb/");
}
