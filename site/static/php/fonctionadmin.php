<?php
    require_once("fonction.php");
    session_start();
    $sql = connexion();

    /*Suppression des suggestions*/
    if(isset($_GET['supprimer_suggestions'])) {
        $sugg = $_GET["supsugg"];

        // Requête de suppression
        $requete = $sql->prepare("DELETE FROM SUGGESTIONS WHERE Suggestion=:s;");
        $requete->bindParam(":s", $sugg);
        $requete->execute();

        // Redirection vers la page admin.php
        header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]). "&alert=supprimer");
        exit(); // Terminer le script après la redirection
    }

    /*Ajout de clé*/
    if(isset($_GET['keysubmit'])) {
        $clef = $_GET["addkey"];
        $clefsens = $_GET["clefsens"];

        // Requête pour vérifier la présence de la clé
        $requete = $sql->prepare("SELECT * FROM CLEF WHERE Clef = :c;");
        $requete->bindParam(":c", $clef);
        $requete->execute();
        $result = $requete->fetch();
        if ($result) {
            header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]) . "&alert=dejaclef");
            exit();

        } else {
            // Requête pour ajouter la clé
            $requete = $sql->prepare("INSERT INTO CLEF(Clef, Sens) VALUES (:c, :s);");
            $requete->bindParam(":c", $clef);
            $requete->bindParam(":s", $clefsens);
            $requete->execute();

            header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]) . "&alert=success");
            exit(); // Terminer le script après la redirection

        }

    }

    /*Ajout de kanji*/
    if(isset($_GET["kanjisubmit"])){

        $kanji = $_GET["kanji"];
        $key = $_GET["key"];
        $sens = $_GET["sens"];
        $kunyomi = $_GET["kunyomi"];
        $onyomi = $_GET["onyomi"];
        $nbtraits = $_GET["nbtraits"];
        $jlpt = $_GET["jlpt"];

        // Requête pour vérifier la présence du Kanji
        $requete = $sql->prepare("SELECT COUNT(*) FROM KANJIS WHERE Kanji = :k;");
        $requete->bindParam(":k", $kanji);
        $requete->execute();
        $count = $requete->fetchColumn();

        if ($count == 0) {
            // Requête pour vérifier la présence de la clé et récupérer la clé primaire
            $requete = $sql->prepare("SELECT IdClef FROM CLEF WHERE Clef = :c;");
            $requete->bindParam(":c", $key);
            $requete->execute();
            $result = $requete->fetch();

            if ($result) {
                $idclef = $result["IdClef"];

                // Requête pour ajouter le Kanji
                $requete = $sql->prepare("INSERT INTO KANJIS(Kanji, IdClef, KunYomi, OnYomi, Sens, NombreDeTraits, Niveau) VALUES (:k, :ic, :kun, :on, :sens, :nbt, :niveau);");
                $requete->bindParam(":k", $kanji);
                $requete->bindParam(":ic", $idclef);
                $requete->bindParam(":kun", $kunyomi);
                $requete->bindParam(":on", $onyomi);
                $requete->bindParam(":sens", $sens);
                $requete->bindParam(":nbt", $nbtraits);
                $requete->bindParam(":niveau", $jlpt);
                $requete->execute();

                header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]). "&alert=success");
                exit(); // Terminer le script après la redirection

            } else {
                header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]) . "&alert=clefmanquante");
                exit();
            }
        } else {
            header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]) . "&alert=dejakanji");
            exit();
        }

    }


    /*Ajout d'un mot et d'une phrase*/
    if(isset($_GET["wordsubmit"])){

        $requiredkanji = $_GET["requiredkanji"];
        $mot = $_GET["mot"];
        $motlecture = $_GET["motlecture"];
        $mottraduction = $_GET["mottraduction"];
        $phrase = $_GET["phrase"];
        $phraselecture = $_GET["phraselecture"];
        $phrasetraduction = $_GET["phrasetraduction"];

        // Requête pour vérifier la présence du Kanji et récupérer la clé primaire
        $requete = $sql->prepare("SELECT IdKanji FROM KANJIS WHERE Kanji = :k;");
        $requete->bindParam(":k", $requiredkanji);
        $requete->execute();
        $result = $requete->fetch();

        if ($result) {
            $idkanji = $result["IdKanji"];

            // Requête pour vérifier la présence du mot
            $requete = $sql->prepare("SELECT Mot FROM MOTS WHERE Mot = :m;");
            $requete->bindParam(":m", $mot);
            $requete->execute();
            $result = $requete->fetch();

            if ($result) {
                header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]). "&alert=dejamot");
                exit(); // Terminer le script après la redirection
            }
            else {
                // Requête pour ajouter le mot
                $requete = $sql->prepare("INSERT INTO MOTS(IdKanji, Mot, Prononciation, Sens) VALUES (:ik,:mot,:ml,:mt)");
                $requete->bindParam(":ik", $idkanji);
                $requete->bindParam(":mot", $mot);
                $requete->bindParam(":ml", $motlecture);
                $requete->bindParam(":mt", $mottraduction);
                $requete->execute();

                // Requête pour récupérer la clé primaire du mot
                $requete = $sql->prepare("SELECT IdMot FROM MOTS WHERE Mot = :m;");
                $requete->bindParam(":m", $mot);
                $requete->execute();
                $result = $requete->fetch();
                $idmot = $result["IdMot"];

                // Requête pour ajouter la phrase
                $requete = $sql->prepare("INSERT INTO PHRASES(idMot, Phrase, Prononciation, Traduction) VALUES (:im,:phrase,:pl,:pt);");
                $requete->bindParam(":im", $idmot);
                $requete->bindParam(":phrase", $phrase);
                $requete->bindParam(":pl", $phraselecture);
                $requete->bindParam(":pt", $phrasetraduction);
                $requete->execute();

                header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]). "&alert=success");
                exit();
            }
        }
        else {
            header("Location: admin.php?login=" . urlencode($_SESSION["login"]) . "&pw=" . urlencode($_SESSION["pw"]). "&alert=kanjimanquant");
            exit();
        }

    }

?>