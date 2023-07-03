-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 03 juil. 2023 à 14:59
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `daily-movies`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `content`, `image`, `slug`, `date_creation`) VALUES
(1, 8, 'Uncharted 12', '<p>Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.</p>', '1a2084b66e95840a5e8b6d4a72e11afb.jpg', 'uncharted-film', '2023-06-26 15:37:47'),
(2, 8, 'Uncharted - film 2022', 'Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.', '5983633.jpg', 'uncharted-film', '2023-06-26 15:37:47'),
(3, 8, 'titanic le film  plein de message', '<p>Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.</p>', '48cd1b86be367cca65f2725d01a517a2.jpg', 'titanic-is-the-movie-of-year', '2023-06-26 15:37:47'),
(4, 8, 'avatar volume 2  2023', '<h1>Avatar volume 2</h1><p>Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.</p>', 'ce81b43972bb97c5e35e9bca993f03df.jpg', 'avatar-volume-2-2023', '2023-06-26 15:37:47'),
(5, 8, 'Uncharted - film 2022', 'Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.', '5983635.jpg', 'uncharted-film', '2023-06-26 15:37:47'),
(6, 8, 'bradpitt F QDFQDSFSQDFQSDWFSQDFSQDFSDF', '<p>Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.</p>', 'ca385f0df5a60cea7349b4179b1c6341.jpg', 'uncharted-film-7', '2023-06-26 15:37:47'),
(7, 8, 'Uncharted - film 2022', '<p>Nathan Drake, voleur astucieux et intrépide, est recruté par le chasseur de trésors chevronné Victor « Sully » Sullivan pour retrouver la fortune de Ferdinand Magellan, disparue il y a 500 ans. Ce qui ressemble d’abord à un simple casse devient finalement une course effrénée autour du globe pour s’emparer du trésor avant l’impitoyable Moncada, qui est persuadé que sa famille est l’héritière légitime de cette fortune. Si Nathan et Sully réussissent à déchiffrer les indices et résoudre l’un des plus anciens mystères du monde, ils pourraient rafler la somme de 5 milliards de dollars et peut-être même retrouver le frère de Nathan, disparu depuis longtemps… mais encore faudrait-il qu’ils apprennent à travailler ensemble.</p>', '96ec561d06e9f2877bf1a00167699bb4.jpg', 'uncharted-film-6', '2023-06-26 21:37:47'),
(8, 2, 'the-witcher-saison-3', '<h1><strong>THE WITCHER&nbsp;</strong></h1><p><br><strong>Vous pouvez partager un article en cliquant sur les icônes de partage en haut à droite de celui-ci.&nbsp;</strong><br>La reproduction totale ou partielle d’un article, sans l’autorisation écrite et préalable du <a href=\"https://www.lemonde.fr\">Monde</a>, est strictement interdite.&nbsp;<br>Pour plus d’informations, consultez nos <a href=\"https://moncompte.lemonde.fr/cgv\">conditions générales de vente</a>.&nbsp;<br>Pour toute demande d’autorisation, contactez <a href=\"mailto:syndication@lemonde.fr\">syndication@lemonde.fr</a>.&nbsp;<br><i>En tant qu’abonné, vous pouvez offrir jusqu’à cinq articles par mois à l’un de vos proches grâce à la fonctionnalité « Offrir un article ».</i>&nbsp;<br><br><a href=\"https://www.lemonde.fr/culture/article/2020/01/03/the-witcher-l-adaptation-poussive-d-une-saga-a-succes_6024747_3246.html\">https://www.lemonde.fr/culture/article/2020/01/03/the-witcher-l-adaptation-poussive-d-une-saga-a-succes_6024747_3246.html</a><br><br>ma liste&nbsp;</p><ol><li>ma liste&nbsp;</li><li>ma liste&nbsp;</li><li>ma liste&nbsp;</li><li>ma liste&nbsp;</li></ol><p>Sang, sexe, épées, dragons… Vous ne regardez pas <i>Game of Thrones </i>mais bien <i>The Witcher</i>, la dernière production originale de Netflix, qui s’aventure sur les terres convoitées de l’<i>heroic fantasy</i>. La première saison, mise en ligne le 21&nbsp;décembre, est l’adaptation d’une adaptation. Elle puise son scénario dans une fresque médiévale fantastique écrite par l’écrivain polonais Andrzej Sapkowski depuis les années 1990, mais son esthétique se rapproche plutôt des jeux vidéo qui en sont tirés, vendus par dizaines de millions d’exemplaires dans les années 2010.</p><p>On y suit les aventures de Geralt de Riv&nbsp;: le Witcher (le Sorceleur), joué ici par Henry Cavill. Ce mutant taiseux aux cheveux blancs attachés en chignon gagne sa croûte en débarrassant les villageois, seigneurs ou intrigants des monstres et malédictions qui hantent les monts et forêts du continent. Alors que les épisodes s’enchaînent comme un recueil de nouvelles, l’arc principal et le destin des personnages – la sorcière Yennefer, la princesse Cirilla, le barde Jaskier… – suivent à la lettre les écrits de Sapkowski et conservent leur noirceur slave.</p><h2>Effets spéciaux faiblards</h2><p>Peu de bons sentiments habitent les contrées parcourues par Geralt sur son cheval Ablette. Les humains ont salement bousculé la vie des elfes et nains locaux, et le royaume de Nilfgaard veut étendre par le sang son empire sur diverses provinces du Nord. Trahisons, corruption, intervention de forces surnaturelles… Les rares moments de répit ont lieu dans des banquets où l’on finit tout de même par se battre, ou dans un bain en galante compagnie.</p><p>Ce mélange d’intrigues politiques, de destins hantés, de batailles rangées, de monstres et de romance donne à <i>The Witcher</i> un air de<i> </i>soap opera coincé dans un remake dépressif du <i>Seigneur des anneaux</i>. Les allergiques au fantastique<i> </i>iront d’emblée voir ailleurs&nbsp;: n’est pas <i>Game of Thrones</i>&nbsp;qui veut. Mais les novices bienveillants pourraient, eux aussi, rapidement lâcher cette première saison aux effets spéciaux faiblards.</p><p>Après un premier épisode bâclé, on parvient toutefois à suivre l’odyssée de Geralt grâce à une réalisation nerveuse et à une brochette de personnages féminins charismatiques, qui volent finalement la vedette à un Sorceleur prisonnier de son modèle numérique&nbsp;: l’acteur Henry Cavill va jusqu’à reprendre le timbre de voix et les grognements de son double de pixels.</p>', 'd2b3bd3c4d906bd5cc321b3c001a9c38.jpg', 'the-witcher-saison-3', '2023-07-01 23:51:41'),
(9, 2, 'this-witcher-saison-2', '<p>Ce mélange d’intrigues politiques, de destins hantés, de batailles rangées, de monstres et de romance donne à <i>The Witcher</i> un air de<i> </i>soap opera coincé dans un remake dépressif du <i>Seigneur des anneaux</i>. Les allergiques au fantastique<i> </i>iront d’emblée voir ailleurs&nbsp;: n’est pas <i>Game of Thrones</i>&nbsp;qui veut. Mais les novices bienveillants pourraient, eux aussi, rapidement lâcher cette première saison aux effets spéciaux faiblards.</p><p>Après un premier épisode bâclé, on parvient toutefois à suivre l’odyssée de Geralt grâce à une réalisation nerveuse et à une brochette de personnages féminins charismatiques, qui volent finalement la vedette à un Sorceleur prisonnier de son modèle numérique&nbsp;: l’acteur Henry Cavill va jusqu’à reprendre le timbre de voix et les grognements de son double de pixels.</p><p><br>&nbsp;</p>', '94c8418f98d8fbdde2de5a1f9589fc1c.jpg', 'this-witcher-saison-2', '2023-07-02 00:06:43'),
(11, 2, 'les chevalier du zodiaque : le film', '<h2><strong>SEIYA C’EST PLUS FORT QUE TOI</strong></h2><p>Paru pour la première fois en 1986,&nbsp;<i><strong>Saint Seiya&nbsp;</strong></i>(<i><strong>Les Chevaliers du Zodiaque</strong></i>&nbsp;en France) est considéré aujourd’hui comme l’un des parrains du genre shonen (manga destiné à un public d’adolescents) aux côtés, entre autres, de <i><strong>Dragon Ball</strong></i> ou de <i><strong>Yu Yu Hakusho</strong></i>. Librement inspirée de plusieurs mythologies (en particulier la gréco-romaine), son histoire raconte celle de Seiya, un jeune chevalier entraîné à protéger <strong>la réincarnation d’Athéna : Saori Kido.&nbsp;</strong></p><p>Amitié, dépassement de soi, affrontements aussi dantesques que désespérés... Voilà toutes les choses qui ont fait l’essence de <i><strong>Saint Seiya</strong></i> et de ses guerriers d’Athéna. Une essence qui <strong>a influencé toute la génération de shonens qui lui a succédé</strong> et dont on aurait aimé retrouver la trace dans sa nouvelle adaptation de 2023 : <i><strong>Les</strong></i>&nbsp;<i><strong>Chevaliers du Zodiaque</strong></i>. Car si le film réalisé par Tomasz Baginski tente tant bien que mal de reconstituer des bribes de la mythologie du manga (seule sa scène d’introduction y arrive un peu) pour faire croire qu’il en est l’héritier, il est incapable de&nbsp;comprendre ce qu\'il adapte.</p>', '2b90e30e189c2997e723efac6637959c.jpg', 'les-chevalier-du-zodiaque-le-film', '2023-07-02 00:26:29'),
(12, 2, 'Dune vol 2 -  enfin une date  pour la sortie', '<p><i><strong>Dune</strong></i><strong>, célèbre roman de science-fiction</strong> de <a href=\"https://www.lemonde.fr/archives/article/1986/02/14/la-mort-de-l-ecrivain-de-science-fiction-frank-herbert_2925767_1819218.html\">Frank Herbert</a>, publié en&nbsp;1965, a suscité, depuis lors, moult projets d’adaptation, où se sont cassé les dents nombre de studios, de scénaristes et de cinéastes, et non des moindres (David Lynch, <a href=\"https://www.lemonde.fr/cinema/article/2016/03/15/jodorowsky-s-dune-le-sage-recit-d-un-sommet-de-folie-cinematographique_4882866_3476.html\">Alejandro Jodorowsky</a>, Ridley Scott…).</p><p>Il revient aujourd’hui à Denis Villeneuve de relever le défi. Formé à l’université de Québec, compagnon de route tardif d’<a href=\"https://www.lemonde.fr/archives/article/1986/07/21/la-longue-traversee-de-pierre-perrault_2933846_1819218.html\">un génie du cinéma direct, Pierre Perrault</a>, en compagnie duquel il partit étudier les bœufs musqués au pôle Nord, produit lui-même en ses débuts par l’estimable Office national du film du Canada, le natif de Trois-Rivières n’en rêvait pas moins d’Hollywood. Tout le monde peut s’égarer.</p>', '67c8e73865ba8fc9cfe628501b6b34c1.jpg', 'dune-vol-2-date-de-sortie', '2023-07-02 00:44:01'),
(13, 2, 'avengers infinity war 2', '<p><a href=\"https://www.phonandroid.com/disney-lancement-le-12-novembre-2019-au-prix-de-699-dollars-par-mois.html\">Le service de streaming Disney+ en France</a> est arrivé en avril 2020. C\'est l\'occasion pour les fans de regarder les contenus de quatre filiales détenues par le groupe : Pixar Animation Studios, Marvel Studios, Lucasfilm et National Geographic.</p><p>L\'<a href=\"https://www.phonandroid.com/differents-tarifs-abonnements-disney-plus-france.html\">abonnement pour un mois est sans engagement et coute 8.99 euros</a> ou bien vous pouvez choisir une durée d\'un an au tarif de 89.99 euros, ce qui revient à 7,4 euros par mois. Aussi, sachez que le service <a href=\"https://www.phonandroid.com/disney-plus-chez-canal-plus-meilleures-offres.html\">Disney+ est également inclus dans certaines offres Canal+ à prix réduit</a>. Avec tout ceci, vous avez du choix pour trouver l\'offre qui correspond le mieux à vos besoins.</p><h2><strong>QUEL EST L\'ORDRE DE VISIONNAGE DES FILMS MARVEL ?</strong></h2><p>À l\'instar de sagas comme X-Men ou <a href=\"https://www.phonandroid.com/star-wars-dans-quel-ordre-regarder-les-films-et-les-series-sur-disney.html\">Star Wars</a>, <strong>les films faisant partie de l\'ensemble du MCU</strong> (Marvel Cinematic Universe) sortent souvent dans un ordre qui n\'a rien à voir avec leur ordre respectif de visionnage. Si vous souhaitez connaitre <strong>l\'ordre chronologique</strong>, le voici :</p><ol><li>Captain America : First Avenger (2011)</li><li>Agent Carter (2015-2016)</li><li>Captain Marvel (2019)</li><li>Iron Man (2008)</li><li>Iron Man 2 (2010)</li><li>L’Incroyable Hulk (2008)</li><li>Thor (2011)</li><li>Avengers (2012)</li><li>Thor : Le Monde des ténèbres (2013)</li><li>Iron Man 3 (2013)</li><li>Captain America : Le Soldat de l’hiver (2014)</li><li>Les Gardiens de la Galaxie (2014)</li><li>Les Gardiens de la galaxie Vol. 2 (2017)</li><li>Avengers : L’Ère d’Ultron (2015)</li><li>Ant-Man (2015)</li><li>Captain America : Civil War (2016)</li><li>Black Widow (2021)</li><li>Black Panther (2018)</li><li>Spider-Man : Homecoming (2017)</li><li>Doctor Strange (2016)</li><li>Thor Ragnarok (2017)</li><li>Ant-Man et la guêpe (2018)</li><li>Avengers : Infinity War (2018)</li><li>Avengers : Endgame (2019)</li><li>Loki (série TV – 2021)</li><li>What If…? (série TV animée – 2021)</li><li>WandaVision (série TV – 2021)</li><li>Falcon et le Soldat de l\'hiver (série TV – 2021)</li><li>Shang-Chi (2021)</li><li>Les Éternels (2021)</li><li>She-Hulk (série TV – 2022)</li><li>Spider-Man : Far From Home (2019)</li><li>Spider-Man : No Way Home (2021)</li><li>Doctor Strange in the Multiverse of Madness (2022)</li><li>Hawkeye (série TV – 2021)</li><li>Moon Knight (série TV – 2022)</li><li>Miss Marvel (série TV – 2022)</li><li>Thor : Love and Thunder (2021)</li><li>Black Panther : Wakanda Forever (2022)</li><li>Werewolf by Night (2022)</li><li>Ant-Man et la Guêpe : Quantumania (2023)</li><li>Secret Invasion (2023)</li><li>Les Gardiens de la Galaxie Vol. 3 (2023)</li></ol><p>&nbsp;</p><p>Comme vous pouvez le constater, nous avons même inclus <strong>Doctor Strange in the Multiverse of Madness</strong>, sorti en salle le 4 mai 2022. Notez que la place de ce dernier dans la timeline du MCU n\'a pas encore été officialisée par Marvel. Quoi qu\'il en soit, celui-ci se déroule après Spider-Man No Way Home et WandaVision, et vraisemblablement à peu près au même moment que Moon Knight. Le même “souci” se pose pour Werewolf by Night, un film disponible uniquement sur Disney+. Selon l\'un des indices laissés dans le film, il est certain que ce dernier se déroule après 1986. Mais quand exactement ? Disney+ le place en bout de chaîne dans son classement chronologique, sans donner davantage de précision. C\'est donc dans les dernières positions que nous l\'avons également placé.</p><p>Et comme vous pouvez le remarquer, nous avons intégré à notre liste Ant-Man et la Guêpe : Quantumania. Ce nouveau film, sorti le 15 février 2023 au cinéma, a la lourde tâche d\'ouvrir la phase 5 du Marvel Cinematic Universe.&nbsp;En tout et pour tout, il y a <strong>32 films Marvel</strong> (sans compter les séries TV) qui représentent près de 60 heures de visionnage. Et en mai 2023, le troisième volume des Gardiens de la Galaxie est venu grossir les rangs. Cet épisode important décrit la vie des Gardiens après Thanos et la perte de Gamora.</p>', '83d150be1cecc45b684b14154fa0e095.jpg', 'avengers-infinity-war', '2023-07-02 22:54:21');

-- --------------------------------------------------------

--
-- Structure de la table `article_categories`
--

CREATE TABLE `article_categories` (
  `article_id` int NOT NULL,
  `categorie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article_categories`
--

INSERT INTO `article_categories` (`article_id`, `categorie_id`) VALUES
(1, 2),
(6, 2),
(7, 2),
(13, 2),
(3, 3),
(8, 3),
(13, 3),
(13, 4),
(2, 5),
(6, 5),
(13, 5),
(7, 6),
(13, 6),
(3, 7),
(13, 7),
(13, 8),
(6, 9),
(13, 9),
(4, 10),
(13, 10),
(9, 12),
(12, 13),
(13, 13),
(13, 14),
(4, 15),
(11, 15),
(13, 15),
(11, 16),
(13, 16),
(6, 17);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(2, 'action', 'action'),
(3, 'aventure', 'aventure'),
(4, 'animation', 'animation'),
(5, 'comédie', 'comedie'),
(6, 'comédie romantique', 'comedie-romantique'),
(7, 'crime', 'crime'),
(8, 'documentaire', 'documentaire'),
(9, 'drame', 'drame'),
(10, 'fantastique', 'fantastique'),
(11, 'horreur', 'horreur'),
(12, 'musical', 'musical'),
(13, 'mystère', 'mystere'),
(14, 'romance', 'romance'),
(15, 'science-fiction', 'science-fiction'),
(16, 'suspense', 'suspense'),
(17, 'thriller', 'thriller'),
(18, 'western', 'western'),
(19, 'biographie', 'biographie'),
(20, 'historique', 'historique'),
(21, 'guerre', 'guerre');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rgpd` tinyint(1) NOT NULL DEFAULT '0',
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mdp`, `rgpd`, `date_inscription`) VALUES
(1, 'toutou', 'tata', 'newbie89@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$QUVkYy5lcDVmYXFETk1iRg$ELUul35YkiLvf2/SmW6haldn1ueywLTwU92p9TDIqZU', 0, '2023-06-26 15:38:42'),
(2, 'hamid', 'hamid', 'moucci@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$bmdQa01pN3lWSWRtZGF3cw$0/TaOnVjf4sYZEcngDa7wNDAd//sUrvjPXsgj4Rxka0', 1, '2023-06-27 16:45:19'),
(8, 'moucci', 'hamid', 'moucci89@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$L3YwQWFKa3hpWWI0U1E0cg$62Q7j2yXcU2AFD6Ttr9cjJQauiu+KZCnsGA1QdUEXwY', 1, '2023-06-27 16:56:56');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_user` (`user_id`);

--
-- Index pour la table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`article_id`,`categorie_id`),
  ADD KEY `id_categorie_unique` (`categorie_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cat_lien` (`name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `article_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `article_categories`
--
ALTER TABLE `article_categories`
  ADD CONSTRAINT `id_article_unique` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_categorie_unique` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
