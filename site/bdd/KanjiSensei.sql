-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 29 mai 2023 à 20:38
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `KanjiSensei`
--

-- --------------------------------------------------------

--
-- Structure de la table `ADMIN`
--

CREATE TABLE `ADMIN` (
  `IdAdmin` int(11) NOT NULL,
  `Login` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ADMIN`
--

INSERT INTO `ADMIN` (`IdAdmin`, `Login`, `Password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `CLEF`
--

CREATE TABLE `CLEF` (
  `IdClef` int(11) NOT NULL,
  `Clef` varchar(1) NOT NULL,
  `Sens` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `CLEF`
--

INSERT INTO `CLEF` (`IdClef`, `Clef`, `Sens`) VALUES
(1, '一', 'clé de un'),
(2, '人', 'clé de l\'homme'),
(3, '音', 'clé du son'),
(4, '儿', 'clé des jambes'),
(5, '雨', 'clé de la pluie'),
(6, '火', 'clé du feu'),
(7, '水', 'clé de l\'eau'),
(8, '米', 'clé du riz'),
(9, '八', 'clé du huit'),
(10, '戸', 'clé de la porte'),
(11, '日', 'clé du jour'),
(12, '心', 'clé du cœur '),
(13, '土', 'clé de la terre');

-- --------------------------------------------------------

--
-- Structure de la table `KANJIS`
--

CREATE TABLE `KANJIS` (
  `IdKanji` int(11) NOT NULL,
  `Kanji` varchar(1) NOT NULL,
  `IdClef` int(11) NOT NULL,
  `KunYomi` text,
  `OnYomi` text,
  `Sens` text NOT NULL,
  `NombreDeTraits` int(11) NOT NULL,
  `Niveau` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `KANJIS`
--

INSERT INTO `KANJIS` (`IdKanji`, `Kanji`, `IdClef`, `KunYomi`, `OnYomi`, `Sens`, `NombreDeTraits`, `Niveau`) VALUES
(1, '何', 2, 'nani, nan', 'ka', 'quoi', 7, 'N5'),
(2, '音', 3, 'oto, ne', 'on, in', 'son, bruit', 9, 'N4'),
(3, '光', 4, 'hika, hikari', 'kō', 'lumière, rayon', 6, 'N3'),
(4, '雲', 5, 'kumo, gumo', 'un', 'nuage', 12, 'N2'),
(5, '炎', 6, 'honō', 'en', 'inflammation, flamme, incendie', 8, 'N1'),
(6, '三', 1, 'mi, mitsu', 'san, zō', 'trois', 3, 'N5');

-- --------------------------------------------------------

--
-- Structure de la table `MOTS`
--

CREATE TABLE `MOTS` (
  `IdMot` int(11) NOT NULL,
  `IdKanji` int(11) NOT NULL,
  `Mot` text NOT NULL,
  `Prononciation` text NOT NULL,
  `Sens` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `MOTS`
--

INSERT INTO `MOTS` (`IdMot`, `IdKanji`, `Mot`, `Prononciation`, `Sens`) VALUES
(1, 1, '何', 'nani', 'quoi'),
(2, 1, '何時', 'nanji', 'quelle heure'),
(3, 1, '何度も', 'nandomo', 'plusieurs fois'),
(4, 2, '音楽', 'ongaku', 'musique'),
(5, 2, '音', 'oto', 'bruit'),
(6, 3, '光る', 'hikaru', 'briller'),
(7, 3, '光栄', 'kōei', 'honneur'),
(8, 4, '雲', 'kumo', 'nuage'),
(9, 4, '雲霧', 'unkiri', 'brouillard'),
(10, 5, '炎', 'honō', 'flamme'),
(11, 5, '肺炎', 'haien', 'pneumonie'),
(12, 1, '何曜日', 'nanyoubi', 'quel jour');

-- --------------------------------------------------------

--
-- Structure de la table `PHRASES`
--

CREATE TABLE `PHRASES` (
  `idPhrase` int(11) NOT NULL,
  `idMot` int(11) NOT NULL,
  `Phrase` text NOT NULL,
  `Prononciation` text NOT NULL,
  `Traduction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `PHRASES`
--

INSERT INTO `PHRASES` (`idPhrase`, `idMot`, `Phrase`, `Prononciation`, `Traduction`) VALUES
(1, 1, '何をしているの？', 'nani o shiteiru no?', 'Que fais-tu?'),
(2, 2, '今何時ですか？', 'ima nanji desu ka?', 'Quelle heure est-il?'),
(3, 3, '彼女は何度もここに来たことがある', 'kanojo wa nandomo koko ni kita koto ga aru.', 'Elle est venue ici plusieurs fois.'),
(4, 4, '彼の音楽はとても美しいです。', 'kare no ongaku wa totemo utsukushii desu', 'Sa musique est très belle.'),
(5, 5, 'この部屋で音を立てないでください。', 'kono heya de oto o tatenaide kudasai', 'S\'il vous plaît, ne faites pas de bruit dans cette pièce.'),
(6, 6, '月が光っている。', 'tsuki ga hikatte iru', 'La lune brille.'),
(7, 7, 'この賞を受賞できることは大変な光栄です。', 'kono shou o jushou dekiru koto wa taihen na kouei desu.', 'C\'est un grand honneur de pouvoir recevoir ce prix.'),
(8, 8, '空には白い雲が浮かんでいる。', 'sora ni wa shiroi kumo ga ukande iru.', 'Il y a des nuages blancs flottant dans le ciel.'),
(9, 9, '霧の中で車を運転するのは危険です。', 'kiri no naka de kuruma o unten suru no wa kiken desu', 'Conduire une voiture dans le brouillard est dangereux.'),
(10, 10, '火事で家が炎に包まれました。', 'kaji de ie ga honoo ni tsutsu mare mashita', 'Ma maison a été enveloppée par les flammes dans un incendie.'),
(11, 11, '新しいコロナウイルスの肺炎に感染した人が増えています。', 'Atarashii koronauirusu no haien ni kansen shita hito ga fuete imasu', 'Le nombre de personnes infectées par la pneumonie due au nouveau coronavirus augmente.'),
(12, 12, '今日は何曜日ですか？', 'kyou wa nanyoubi desu ka', 'Quel jour sommes-nous aujourd\'hui ?');

-- --------------------------------------------------------

--
-- Structure de la table `SUGGESTIONS`
--

CREATE TABLE `SUGGESTIONS` (
  `IdSuggestion` int(11) NOT NULL,
  `Suggestion` text NOT NULL,
  `Mail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `SUGGESTIONS`
--

INSERT INTO `SUGGESTIONS` (`IdSuggestion`, `Suggestion`, `Mail`) VALUES
(4, '花', 'kenza.piter@gmail.com'),
(5, '花', 'kenza.piter@gmail.com'),
(6, '空', 'me@gmail.com'),
(10, 'savoir', ''),
(11, '右', ''),
(12, 'watashi', ''),
(13, 'つき', 'kenza.piter@gmail.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD PRIMARY KEY (`IdAdmin`);

--
-- Index pour la table `CLEF`
--
ALTER TABLE `CLEF`
  ADD PRIMARY KEY (`IdClef`);

--
-- Index pour la table `KANJIS`
--
ALTER TABLE `KANJIS`
  ADD PRIMARY KEY (`IdKanji`);

--
-- Index pour la table `MOTS`
--
ALTER TABLE `MOTS`
  ADD PRIMARY KEY (`IdMot`);

--
-- Index pour la table `PHRASES`
--
ALTER TABLE `PHRASES`
  ADD PRIMARY KEY (`idPhrase`);

--
-- Index pour la table `SUGGESTIONS`
--
ALTER TABLE `SUGGESTIONS`
  ADD PRIMARY KEY (`IdSuggestion`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ADMIN`
--
ALTER TABLE `ADMIN`
  MODIFY `IdAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `CLEF`
--
ALTER TABLE `CLEF`
  MODIFY `IdClef` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `KANJIS`
--
ALTER TABLE `KANJIS`
  MODIFY `IdKanji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `MOTS`
--
ALTER TABLE `MOTS`
  MODIFY `IdMot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `PHRASES`
--
ALTER TABLE `PHRASES`
  MODIFY `idPhrase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `SUGGESTIONS`
--
ALTER TABLE `SUGGESTIONS`
  MODIFY `IdSuggestion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
