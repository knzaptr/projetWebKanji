<!DOCTYPE html>
<html>
<head>
    <title>ks</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- Dépendances CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Dépendances JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Fichiers JavaScript personnalisés -->
    <script src="../js/nightmode.js"></script>
    <script src="../js/suggestion.js"></script>
    <!-- Fichier CSS personnalisé -->
    <link id="main" href="../css/main.css" rel="stylesheet">
</head>
<body class="modejour">
<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-transparent">
    <div class="container-fluid">
        <a class="navbar-brand" href="../../templates/index.html">KanjiSensei</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../templates/index.html">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../templates/kanji.html">Kanji</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../templates/admin.html">Admin</a>
                </li>
            </ul>
        </div>
        <!-- Commutateur de mode nuit -->
        <div class="form-check form-switch" style="padding-right: 2%">
            <input class="form-check-input" type="checkbox" role="switch" id="night">
            <label class="form-check-label" for="night" id="moon_light"><img id="test" src="../images/1.png" width="20" height="20" alt="soleil dans un croissant de lune"></label>
        </div>
    </div>
</nav>
<hr>

<!-- Barre de recherche de kanji -->
<div id="kanji" class="row justify-content-around">
    <div id="kdiv" class="col-10">
        <h4>Kanji</h4>
        <form id="kanjiform" method="get" action="./kanji.php">
            <div class="row">
                <div class="col">
                    <input id="user_kanji" name="kanji" type="text" class="form-control" required>
                </div>
                <div class="col">
                    <select id="type" class="form-select" name="type">
                        <option value="kanji">Kanji</option>
                        <option value="KunYomi">KunYomi</option>
                        <option value="OnYomi">OnYomi</option>
                        <option value="Sens">Sens</option>
                        <option value="Niveau">Niveau (1 à 5)</option>
                    </select>
                </div>
                <div class="col">
                    <input id="ksubmit" type="submit" name="ksubmit">
                </div>
            </div>
        </form>
    </div>

    <!-- Affichage des résultats : kanji -->
        <?php
        if(isset($_GET['ksubmit'])) {

            require_once("fonction.php");
            $sql = connexion();

            $k = $_GET["kanji"];
            $type = $_GET["type"];
            $kanjiList = array();

            $requeteCheck = $sql->prepare("SELECT * FROM KANJIS WHERE $type LIKE CONCAT('%', :k, '%');");
            $requeteCheck->bindParam(":k", $k);
            $requeteCheck->execute();

            if($requeteCheck->rowCount() == 0){
                echo "<div class='col-10' style='text-align: center;'>Désolée cette information n'est pas encore sur le site.
                        <p>Vous pouvez en faire une suggestion en cliquant sur la bulle en bas à droite</p>
                        </div>";
            }
            else {
                echo "<div class='col-10'>";
                echo "<table class='table'>
                  <thead>
                    <tr>
                      <th scope='col'>Kanji</th>
                      <th scope='col'>Clef</th>
                      <th scope='col'>KunYomi</th>
                      <th scope='col'>OnYomi</th>
                      <th scope='col'>Signification</th>
                      <th scope='col'>JLPT</th>
                    </tr>
                  </thead>
                  <tbody>";

                while ($ligne = $requeteCheck->fetch(PDO::FETCH_OBJ)) {
                    $kanjiList[] = $ligne->Kanji;
                    echo "<tr>";
                    echo '<th>' . $ligne->Kanji . '</a></th>';

                    $requeteKey = $sql->prepare("SELECT * FROM CLEF WHERE IdClef=:idkey;");
                    $requeteKey->bindParam(":idkey", $ligne->IdClef);
                    $requeteKey->execute();
                    $resultKey = $requeteKey->fetch();
                    echo '<td title="' . $resultKey["Sens"] . '">' . $resultKey["Clef"] . '</td>';
                    echo '<td>' . $ligne->KunYomi . '</td>';
                    echo '<td>' . $ligne->OnYomi . '</td>';
                    echo '<td>' . $ligne->Sens . '</td>';
                    echo '<td>' . $ligne->Niveau . '</td>';
                    echo "</tr>";
                }
                echo "</tbody>
            </table>
            </div>";
            }
        }
        ?>
        <!-- Affichage des résultats : mots -->
        <?php
            if(isset($_GET['ksubmit'])) {
                if ($requeteCheck->rowCount() > 0) {
                    echo "<div id='exemple' class='row justify-content-around'>
        <div class='col-5'>";
                    echo "<table class='table'>
                  <thead>
                    <tr>
                      <th scope='col'>Kanji</th>
                      <th scope='col'>Mot</th>
                      <th scope='col'>Signification</th>
                    </tr>
                  </thead>
                  <tbody>";

                    $motlist = array();
                    foreach ($kanjiList as $kanji) {
                        $requete = $sql->prepare("SELECT * FROM MOTS WHERE IdKanji=(SELECT IdKanji FROM KANJIS WHERE Kanji=:k);");
                        $requete->bindParam(":k", $kanji);
                        $requete->execute();

                        while ($ligne = $requete->fetch(PDO::FETCH_OBJ)) {
                            $motlist [] = $ligne->Mot;
                            echo "<tr>";
                            echo '<th>' . $kanji . '</th>';
                            echo '<td title="' . $ligne->Prononciation . '">' . $ligne->Mot . '</td>';
                            echo '<td>' . $ligne->Sens . '</td>';
                            echo "</tr>";
                        }
                    }
                    echo "</tbody>
            </table>
            </div>
    </div>";
                }
            }
            ?>
    <!-- Affichage des résultats : phrases -->
    <?php
    if(isset($_GET['ksubmit'])) {
        if ($requeteCheck->rowCount() > 0) {
            echo "<div id='exemple' class='row justify-content-around'>
        <div class='col-10'>";
            echo "<table class='table'>
                  <thead>
                    <tr>
                      <th scope='col'>Mot</th>
                      <th scope='col'>Phrase</th>
                      <th scope='col'>Traduction</th>
                    </tr>
                  </thead>
                  <tbody>";
            foreach ($motlist as $mot) {
                $requete = $sql->prepare("SELECT * FROM PHRASES WHERE IdMot=(SELECT IdMot FROM MOTS WHERE Mot=:mot);");
                $requete->bindParam(":mot", $mot);
                $requete->execute();

                while ($ligne = $requete->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    echo '<th>' . $mot . '</a></th>';
                    echo '<td title="' . $ligne->Prononciation . '">' . $ligne->Phrase . '</td>';
                    echo '<td>' . $ligne->Traduction . '</td>';
                    echo "</tr>";
                }
            }
            echo "</tbody>
            </table>
            </div>
    </div>";
        }
    }
    ?>

    <!--Formulaire de suggestion-->
    <div id="contact">
        <div class="card" style="width: 18rem;" id="carddiv">
            <div class="card-body" id="card-body">
                <h4>Suggestion</h4>
                <form method="get" action="../static/php/kanji.php">
                    <label for="user_suggestion">Le mot ou kanji que vous souhaitez découvrir : </label>
                    <input id="user_suggestion" class="form-control" placeholder="pluie, 雨,..." name="user_suggestion" required>

                    <label for="mail">Votre adresse mail pour être prévenu de l'ajout (optionnel): </label>
                    <input type="email" id="mail" class="form-control" placeholder="kanji@sensei.ja" name="mail">

                    <button type="submit" class="btn btn-primary" id="valider" name="suggsubmit">Valider</button>
                </form>
            </div>
        </div>
        <button id="suggbutton" type="submit" style="background-color: transparent;"><img src="../images/bullejour.png" width="50" alt="bullecontact"></button>
    </div>
        <?php
        require_once("fonction.php");
        $sql = connexion();
        if(isset($_GET['suggsubmit'])) {
            $sugg = $_GET["user_suggestion"];
            $mail = $_GET["mail"];

            $requete = $sql->prepare("INSERT INTO SUGGESTIONS(Suggestion, Mail) VALUES (:sugg,:mail);");
            $requete->bindParam(":sugg", $sugg);
            $requete->bindParam(":mail", $mail);
            $requete->execute();
        }
        ?>

    <!--Footer-->
    <footer>
        <div>
            <img src="../images/bird.png" alt="footer"/>
            <p>by Kenza</p>
        </div>
    </footer>
</body>
</html>
