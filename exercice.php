<?php

// eerst oefening van sounya 
$serveur = "localhost";
$port = "3306";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnees = "student";


$mysqli = new mysqli("$serveur:$port", $utilisateur, $motDePasse, $baseDeDonnees);


if ($mysqli->connect_error) {
    die("Échec de la connexion à la base de données: " . $mysqli->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $note = $_POST["note"];

    $insertQuery = "INSERT INTO étudiant (nom, prenom, note) VALUES ('$nom', '$prenom', '$note')";

    if ($mysqli->query($insertQuery) === TRUE) {
        echo "Nouvel étudiant inséré avec succès.";
    } else {
        echo "Erreur lors de l'insertion de l'étudiant : " . $mysqli->error;
    }
}

if (isset($_GET["delete"])) {
    $idToDelete = $_GET["delete"];

    $deleteQuery = "DELETE FROM étudiant WHERE id = $idToDelete";

    if ($mysqli->query($deleteQuery) === TRUE) {
        echo "<p>Étudiant supprimé avec succès.</p>";
    } else {
        echo "Erreur lors de la suppression de l'étudiant : " . $mysqli->error;
    }
}

$sql = "SELECT * FROM étudiant ORDER BY note DESC";

$resultat = $mysqli->query($sql);

if ($resultat->num_rows > 0) {

    echo "<div style='float: left; margin-right: 20px;'>";
    echo "<h2>Étudiants avec les meilleures notes :</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nom</th><th>Prénom</th><th>Note</th></tr>";

    while ($row = $resultat->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['nom']}</td>";
        echo "<td>{$row['prenom']}</td>";
        echo "<td>{$row['note']}/20</td>";
        echo "<td><a href='?delete={$row['id']}'>Supprimer</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "Aucun résultat trouvé.";
}

$mysqli->close();

?>
<div style='float: left;'>
<!-- Formulaire d'insertion -->
<h2>Ajouter un nouvel étudiant :</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Nom: <input type="text" name="nom" required><br>
        Prénom: <input type="text" name="prenom" required><br>
        Note: <input type="number" name="note" min="0" max="20" required><br>
        <input type="submit" value="Ajouter">
    </form>
</div>