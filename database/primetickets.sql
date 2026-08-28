-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2026 at 11:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `primetickets`
--

-- --------------------------------------------------------

--
-- Table structure for table `adrese`
--

CREATE TABLE `adrese` (
  `id` int(10) UNSIGNED NOT NULL,
  `grad` varchar(100) NOT NULL,
  `ulica` varchar(100) NOT NULL,
  `broj` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adrese`
--

INSERT INTO `adrese` (`id`, `grad`, `ulica`, `broj`) VALUES
(1, 'Beograd', 'Bulevar Arsenija Čarnojevića', '58'),
(2, 'Beograd', 'Milentija Popovića', '9'),
(3, 'Novi Sad', 'Sutjeska', '2'),
(4, 'Smederevo', 'Đure Daničića', '6'),
(5, 'Beograd', 'Bulevar Kralja Aleksandra', '298'),
(6, 'Beograd', 'Poenkareova', '32'),
(7, 'Beograd', 'Bulevar vojovode Bojovića', '30a'),
(8, 'Niš', 'Sinđelićev trg', '12'),
(9, 'Beograd', 'Nemanjina', '28');

-- --------------------------------------------------------

--
-- Table structure for table `dogadjaji`
--

CREATE TABLE `dogadjaji` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(150) NOT NULL,
  `datum_vreme` datetime NOT NULL,
  `organizator` varchar(50) NOT NULL,
  `slika` varchar(255) DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `cena` decimal(10,2) DEFAULT NULL,
  `status` enum('aktivan','otkazan') DEFAULT 'aktivan',
  `idSale` int(10) UNSIGNED NOT NULL,
  `idVrste` int(10) UNSIGNED NOT NULL,
  `datum_unosa` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dogadjaji`
--

INSERT INTO `dogadjaji` (`id`, `naziv`, `datum_vreme`, `organizator`, `slika`, `opis`, `cena`, `status`, `idSale`, `idVrste`, `datum_unosa`) VALUES
(1, 'Ja za čuda letim', '2026-03-14 20:00:00', 'Jakov Jozinović', 'JakovNoviSad.png', 'Foto: Danko Simunović\r\nORGANIZATOR/IZVOR: KAMARAD PRODUKCIJA MD', 1800.00, 'aktivan', 3, 1, '2026-02-25 13:43:51'),
(2, 'LEXXINGTON', '2026-03-28 20:00:00', 'Lexington', 'lexington.jpeg', 'ORGANIZATOR/IZVOR: AUDIO VIDEO PRODUKCIJA S. BANOVCI', 1800.00, 'aktivan', 4, 1, '2026-02-25 13:45:59'),
(3, 'Parni Valjak', '2026-05-16 20:00:00', 'Parni Valjak', 'parniV.jpeg', '“U godini u kojoj obilježavamo 50 godina djelovanja, s publikom ćemo kroz koncerte podijeliti sve što smo zajedno stvarali kroz desetljeća, kao i naše novo poglavlje. Pamtimo mnogo predivnih beogradskih koncerata i svaki nastup u Beogradu za nas ima posebno značenje. Vraćamo se na pozornicu Sava Centra, mjesto na kojem smo nekoliko puta s vama podijelili neke od najljepših emocija. Zajednički nazivnik svih ovih godina je ljubav - ljubav prema glazbi, ljubav prema publici i ljubav koju nam publika vraća. Ljubav koja je srž onoga što radimo, ostaje trajna nit koja nas povezuje, a vaša podrška nam je vječna inspiracija.”” poručuju članovi benda predstavljajući krilaticu aktuelne turneje “Ljubav”.', 2800.00, 'aktivan', 2, 2, '2026-02-25 13:48:39'),
(4, 'MISS KITTIN', '2026-03-28 23:00:00', 'Vlada Janjić', 'missK.jpeg', 'Nakon inicijalnog uspeha debitantskog izdanja Champagne EP, objavljenim u duetu sa The Hackerom za DJ Hell-ov International DJ Gigolo, usledila je saradnja sa Felix Da Housecat-om koja je učvrstila njen status međunarodne zvezde. “1982”, “Frank Sinatra” i “Silver Screen” postaju himne koje su mainstream publici pružile redak uvid u beskompromisni zvuk koji je obeležio eru. Producirajući muziku koja je zvuči drsko, duhovito i slobodno Kittin je postala inspiracija i referenca za mnoge izvođače koji su došli posle, i uprkos popularnosti njena uloga u pomeranju granica ponekad je nedovoljno naglašena. Diskografska karijera obuhvata četiri samostalna albuma, još tri u tandemu sa The Hacker-om od kojih je i dalje aktuelni “Third” iz 2022, desetine 12” singlova, nebrojene kolaboracije sa najznačajnijim producentima epohe (Sven Vath, LFO, Oxia, Monika Kruse, Dubfire, Hot Since 82…)', 1390.00, 'aktivan', 6, 3, '2026-02-25 13:53:13'),
(5, 'SAMOSTALNI REFERENTI', '2026-03-06 21:00:00', 'Samostalni referenti', 'sr.jpeg', 'Od okupljanja Samostalnih Referenata 1996. godine do danas, energični koncerti ovog višečlanog benda redovno privlače brojne ljubitelje njihovog muzičkog izraza i ljubitelje neumornog djuskanja. Zvuk Samostalnih Referenata predstavlja autentičnu, plesnu mešavinu ska i pank muzike, sa istaknutim duvačkim sekcijama. Svoj zvuk nazivaju: Belgrade Turbo Ska.\r\nSamostalni Referenti u ovoj godini slave jubilej 30. godina postojanja i prvi u nizu rođendanskih koncerata desiće se u Zappa Bazi 06.03.2026. Za ovaj specijalan jubilej – 30-ti rođendan, bend je pripremio specijalan nastup sa pojačanom duvačkom sekcijom (4 duvača), violinom i harmonikom. Očekuje vas veče puno hitova, ali i nekoliko novih pesama napravljenih baš za ovaj jubilej. Kao podrška ove večeri je DJ Skakavac. Život počinje u tridesetoj ;)…Vidimo se!', 1000.00, 'aktivan', 7, 4, '2026-02-25 13:56:02'),
(6, 'BVANA I DZEZERI vs SALE I SEDLARI', '2026-03-12 21:00:00', 'Bvana i Dzezeri', 'bdss.jpeg', 'Sedlar i Bvana zajedno na bini Zappa Baze\r\nU četvrtak, 12. marta, Zappa Baza biće mesto gde se rep, rokenrol, bluz i fank sreću uživo. Tri sata muzike, energije i neposrednog doživljaja – baš onako kako muzika treba da se doživi.  \r\nVeče otvara Bvana Herbalajzer sa svojim Džezerima iz Lagune, uz prepoznatljivu rep energiju, groove i stav. Kako sam kaže: \r\n-\"Dođite u što većem broju, da mogu bezbedno da se bacim u publiku, a da se ne polomim o pod...“ Poruka jasna, nesvakidašnje dobra atmosfera se podrazumeva!', 1000.00, 'aktivan', 7, 5, '2026-02-25 13:58:13'),
(7, 'Lords of the Sound - Music of Hans Zimmer', '2026-03-25 20:00:00', 'Lords of the Sound', 'lordsofthesoul.jpeg', 'Čuveni simfonijski orkestar „Lords of the Sound“ predstavlja muzički program „The Music of Hans Zimmer“, koji obuhvata najpoznatije kompozicije muzičkog genija našeg vremena — Hansa Cimera.\r\n„The Music of Hans Zimmer“ je uzbudljivo putovanje u svet jedinstvenih zvukova, koje na scenu donosi izuzetnu atmosferu filmskih remek-dela u izvođenju simfonijskog orkestra.\r\nHans Cimer je jedan od najuticajnijih i najistaknutijih autora savremenih filmskih muzika. Potvrdio se kao majstor epskog zvuka, stvarajući nezaboravne muzičke kompozicije za brojne svetske filmske blokbastere.\r\nProgram „The Music of Hans Zimmer“ obuhvata muziku iz kultnih filmova kao što su: „Dina“, „Spider-Man 2“, „The Dark Knight“, „Interstellar“, „Sherlock Holmes“, „Pearl Harbor“, „Gladiator“, „Inception“, „Pirates of the Caribbean“, „Spirit“, „Call of Duty: Modern Warfare 2“, „Madagascar“, „The Lion King“, „007: No Time to Die“i „Man of Steel“.', 4000.00, 'aktivan', 2, 6, '2026-02-25 14:00:36'),
(8, 'Alessandro Safina', '2026-04-28 20:00:00', 'Alessandro Safina', 'AlessandroSafina.jpeg', 'Poznati tenor Alesandro Safina 28. aprila u Sava Center-u\r\nHitovi iz \"Moulin Rouge\"-a, \"Luna\", \"Sarai Qui\" i mnogih drugih uživo pred beogradskom publikom\r\nSvetski poznati italijanski tenor Alessandro Safina održaće koncert u Beogradu 28. aprila u 20 časova u Sava Centru. Ulaznice za ovaj koncert mogu se kupiti na prodajnim mestima Ticket Vision-a i onlajn na tickets.rs.\r\nSafina poseduje jedinstven glas, prirodni šarm i kreativnu hrabrost koji su ga učinili svetski poznatim izvođačem. Dok je izvodio operske arije, sanjao je da učini klasičnu umetnost razumljivom običnom slušaocu. Kao rezultat toga, nastao je novi žanr, koji je umetnik kasnije nazvao \"opera rok\" – stil koji spaja elemente pop muzike, akademskog vokala, soula i mjuzikla.', 3500.00, 'aktivan', 2, 7, '2026-02-25 14:02:14'),
(9, 'DENIS & DENIS', '2026-03-20 21:00:00', 'Denis i Denis', 'DENIS&DENIS.jpeg', 'Ikonični duo Denis & Denis najavio je poslednju turneju. Na mapi oproštajnih koncerata našao se i Beograd. U petak, 20. marta 2026. od 21h u organizaciji koncertne agencije Odličan Hrčak, Denis & Denis će nastupiti u Zappa Bazi. Nakon gotovo četrdeset i pet godina karijere, Marina Perazić i Davor Tolja najavili su oproštajnu turneju pod nazivom „Poslednji program tvog kompjutera“ kojom će se oprostiti od publike i nakon koje više neće nastupati uživo.', 1500.00, 'aktivan', 7, 8, '2026-02-25 14:03:44'),
(10, 'Za večnost', '2026-04-16 20:00:00', 'Lepa Lukić', 'LepaLukić.jpeg', 'Kada se pomene ime Lepe Lukić, ne govori se samo o muzici - govori se o istoriji, tradiciji i emociji koja traje duže od pola veka.\r\nŽena koja je s pravom ponela titulu kraljice narodne muzike, poziva vas na susret koji se pamti celog života.\r\n16. aprila, Plava dvorana Sava Centra biće domaćin spektakla „Za večnost“.', 2000.00, 'aktivan', 2, 9, '2026-02-25 14:05:23'),
(11, 'RAP PEGLANJE', '2026-05-15 21:00:00', 'Zappa GIG', 'RapPreglanje.jpeg', 'Petak 9.1.2026 Zappa Baza postaje dom najvatrenije ženske rap energije na Balkanu. **RAP PEGLANJE** okuplja najjače autorke nove generacije – reperke koje pomeraju granice, ruše norme i donose svež, sirov i autentičan zvuk koji se retko čuje uživo.\r\nOčekuje te noć brutalnih vers-ova, jakih poruka, ludog plesa i scenske energije kakvu može da napravi samo ekipa žena koje znaju ko su i šta donose na scenu.\r\nUz autorke koje beskompromisno pišu, repuju i stvaraju svoju estetiku, večeri daje poseban pečat i **coreo performance** – Likehyena, Helga Oricci kao i plesačice rap kulture domaće scene koje će biti iznenadjenje i koje dižu celu binu na viši nivo.\r\nDJ podrška stiže od DJ Ada i DJ Vanje Bursać, koje garantuju da će ritam celu noć držati publiku u pokretu, bez pauze.', 1000.00, 'aktivan', 7, 10, '2026-02-25 14:08:54'),
(12, 'CONTRART', '2026-02-27 19:00:00', 'Contrart', 'contrast.jpeg', 'ontra Dance Studio iz Niša organizuje humanitarni plesni koncert „Contrart“ koji će se održati 27. februara 2026. godine, sa početkom u 19 časova, na sceni Narodnog pozorišta u Nišu.\r\nKoncert je posvećen podršci Domu za decu i omladinu „Duško Radović“, sa ciljem da se kroz umetnost doprinese zajednici i podigne svest o značaju solidarnosti i brige.\r\nVeče donosi pažljivo oblikovan plesni program u kojem će publika imati priliku da vidi izuzetne koreografije dece i odraslih, kao i umetničku intervenciju i improvizacije prijatelja Contra Dance Studija — Scene u trenu, poznate po autentičnom scenskom izrazu i snažnoj emotivnoj igri.', 1000.00, 'aktivan', 8, 11, '2026-02-25 14:12:25'),
(13, 'CRVENKAPA', '2026-05-02 17:00:00', 'Pozorište', 'CRVENKAPA.png', 'ORGANIZATOR/IZVOR: Pozorište lutaka Pinokio', 500.00, 'aktivan', 5, 12, '2026-02-25 14:15:56'),
(14, 'Predstava \"Prah\"', '2026-03-31 20:00:00', 'Akademija 28', 'prah.jpeg', 'ORGANIZATOR/IZVOR: Nataša Ninković Šarenac PR agencija za organizovanje umetničkih događaja', 1800.00, 'aktivan', 9, 13, '2026-02-25 17:13:53'),
(15, 'musical MOLIÈRE', '2026-10-31 20:00:00', 'MIRSHOW DOO NOVI SAD', 'MOLIÈRE.jpeg', 'Spektakularna premijera „Le spectacle musical MOLIÈRE“ održana je u jesen 2023. godine u Parizu, gde je predstava proglašena najboljim originalnim mjuziklom u Francuskoj, a uz to je osvojila i brojne druge muzičke nagrade. Predstava pripoveda o profesionalnom razvoju i burnom životu slavnog i popularnog dramskog pisca, obuhvatajući ključne etape njegove biografije, počev od mladih dana.', 3000.00, 'aktivan', 2, 14, '2026-02-25 17:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `karte`
--

CREATE TABLE `karte` (
  `id` int(10) UNSIGNED NOT NULL,
  `cena` decimal(10,2) UNSIGNED NOT NULL,
  `idMesta` int(10) UNSIGNED NOT NULL,
  `idDogadjaja` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karte`
--

INSERT INTO `karte` (`id`, `cena`, `idMesta`, `idDogadjaja`) VALUES
(1, 3800.00, 228, 2),
(2, 3800.00, 166, 1),
(3, 4000.00, 123, 10);

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

CREATE TABLE `kategorije` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` enum('Koncert','Pozorište','Sport','Festival','Muzej') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorije`
--

INSERT INTO `kategorije` (`id`, `naziv`) VALUES
(1, 'Koncert'),
(2, 'Pozorište'),
(3, 'Sport'),
(4, 'Festival'),
(5, 'Muzej');

-- --------------------------------------------------------

--
-- Table structure for table `kupovine`
--

CREATE TABLE `kupovine` (
  `id` int(10) UNSIGNED NOT NULL,
  `datum_kupovine` datetime DEFAULT current_timestamp(),
  `ukupnaCena` decimal(10,2) UNSIGNED NOT NULL,
  `idKorisnika` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kupovine`
--

INSERT INTO `kupovine` (`id`, `datum_kupovine`, `ukupnaCena`, `idKorisnika`) VALUES
(1, '2026-02-25 18:58:10', 3800.00, 2),
(2, '2026-02-28 12:53:37', 3800.00, 2),
(3, '2026-03-25 20:22:55', 4000.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `mesta`
--

CREATE TABLE `mesta` (
  `id` int(10) UNSIGNED NOT NULL,
  `red` int(10) UNSIGNED NOT NULL,
  `broj` int(10) UNSIGNED NOT NULL,
  `idSale` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesta`
--

INSERT INTO `mesta` (`id`, `red`, `broj`, `idSale`) VALUES
(1, 1, 1, 1),
(121, 1, 1, 2),
(166, 1, 1, 3),
(226, 1, 1, 4),
(256, 1, 1, 5),
(286, 1, 1, 6),
(316, 1, 1, 7),
(346, 1, 1, 8),
(386, 1, 1, 9),
(2, 1, 2, 1),
(122, 1, 2, 2),
(167, 1, 2, 3),
(227, 1, 2, 4),
(257, 1, 2, 5),
(287, 1, 2, 6),
(317, 1, 2, 7),
(347, 1, 2, 8),
(387, 1, 2, 9),
(3, 1, 3, 1),
(123, 1, 3, 2),
(168, 1, 3, 3),
(228, 1, 3, 4),
(258, 1, 3, 5),
(288, 1, 3, 6),
(318, 1, 3, 7),
(348, 1, 3, 8),
(388, 1, 3, 9),
(4, 1, 4, 1),
(124, 1, 4, 2),
(169, 1, 4, 3),
(229, 1, 4, 4),
(259, 1, 4, 5),
(289, 1, 4, 6),
(319, 1, 4, 7),
(349, 1, 4, 8),
(389, 1, 4, 9),
(5, 1, 5, 1),
(125, 1, 5, 2),
(170, 1, 5, 3),
(230, 1, 5, 4),
(260, 1, 5, 5),
(290, 1, 5, 6),
(320, 1, 5, 7),
(350, 1, 5, 8),
(390, 1, 5, 9),
(6, 1, 6, 1),
(126, 1, 6, 2),
(171, 1, 6, 3),
(231, 1, 6, 4),
(261, 1, 6, 5),
(291, 1, 6, 6),
(321, 1, 6, 7),
(351, 1, 6, 8),
(391, 1, 6, 9),
(7, 1, 7, 1),
(127, 1, 7, 2),
(172, 1, 7, 3),
(232, 1, 7, 4),
(262, 1, 7, 5),
(292, 1, 7, 6),
(322, 1, 7, 7),
(352, 1, 7, 8),
(392, 1, 7, 9),
(8, 1, 8, 1),
(128, 1, 8, 2),
(173, 1, 8, 3),
(233, 1, 8, 4),
(263, 1, 8, 5),
(293, 1, 8, 6),
(323, 1, 8, 7),
(353, 1, 8, 8),
(393, 1, 8, 9),
(9, 1, 9, 1),
(129, 1, 9, 2),
(174, 1, 9, 3),
(234, 1, 9, 4),
(264, 1, 9, 5),
(294, 1, 9, 6),
(324, 1, 9, 7),
(354, 1, 9, 8),
(394, 1, 9, 9),
(10, 1, 10, 1),
(130, 1, 10, 2),
(175, 1, 10, 3),
(235, 1, 10, 4),
(265, 1, 10, 5),
(295, 1, 10, 6),
(325, 1, 10, 7),
(355, 1, 10, 8),
(395, 1, 10, 9),
(11, 1, 11, 1),
(176, 1, 11, 3),
(296, 1, 11, 6),
(326, 1, 11, 7),
(12, 1, 12, 1),
(177, 1, 12, 3),
(297, 1, 12, 6),
(327, 1, 12, 7),
(13, 1, 13, 1),
(178, 1, 13, 3),
(298, 1, 13, 6),
(328, 1, 13, 7),
(14, 1, 14, 1),
(179, 1, 14, 3),
(299, 1, 14, 6),
(329, 1, 14, 7),
(15, 1, 15, 1),
(180, 1, 15, 3),
(300, 1, 15, 6),
(330, 1, 15, 7),
(16, 1, 16, 1),
(181, 1, 16, 3),
(301, 1, 16, 6),
(331, 1, 16, 7),
(17, 1, 17, 1),
(182, 1, 17, 3),
(302, 1, 17, 6),
(332, 1, 17, 7),
(18, 1, 18, 1),
(183, 1, 18, 3),
(303, 1, 18, 6),
(333, 1, 18, 7),
(19, 1, 19, 1),
(184, 1, 19, 3),
(304, 1, 19, 6),
(334, 1, 19, 7),
(20, 1, 20, 1),
(185, 1, 20, 3),
(305, 1, 20, 6),
(335, 1, 20, 7),
(21, 1, 21, 1),
(306, 1, 21, 6),
(336, 1, 21, 7),
(22, 1, 22, 1),
(307, 1, 22, 6),
(337, 1, 22, 7),
(23, 1, 23, 1),
(308, 1, 23, 6),
(338, 1, 23, 7),
(24, 1, 24, 1),
(309, 1, 24, 6),
(339, 1, 24, 7),
(25, 1, 25, 1),
(310, 1, 25, 6),
(340, 1, 25, 7),
(26, 1, 26, 1),
(311, 1, 26, 6),
(341, 1, 26, 7),
(27, 1, 27, 1),
(312, 1, 27, 6),
(342, 1, 27, 7),
(28, 1, 28, 1),
(313, 1, 28, 6),
(343, 1, 28, 7),
(29, 1, 29, 1),
(314, 1, 29, 6),
(344, 1, 29, 7),
(30, 1, 30, 1),
(315, 1, 30, 6),
(345, 1, 30, 7),
(31, 2, 1, 1),
(131, 2, 1, 2),
(186, 2, 1, 3),
(236, 2, 1, 4),
(266, 2, 1, 5),
(356, 2, 1, 8),
(396, 2, 1, 9),
(32, 2, 2, 1),
(132, 2, 2, 2),
(187, 2, 2, 3),
(237, 2, 2, 4),
(267, 2, 2, 5),
(357, 2, 2, 8),
(397, 2, 2, 9),
(33, 2, 3, 1),
(133, 2, 3, 2),
(188, 2, 3, 3),
(238, 2, 3, 4),
(268, 2, 3, 5),
(358, 2, 3, 8),
(398, 2, 3, 9),
(34, 2, 4, 1),
(134, 2, 4, 2),
(189, 2, 4, 3),
(239, 2, 4, 4),
(269, 2, 4, 5),
(359, 2, 4, 8),
(399, 2, 4, 9),
(35, 2, 5, 1),
(135, 2, 5, 2),
(190, 2, 5, 3),
(240, 2, 5, 4),
(270, 2, 5, 5),
(360, 2, 5, 8),
(400, 2, 5, 9),
(36, 2, 6, 1),
(136, 2, 6, 2),
(191, 2, 6, 3),
(241, 2, 6, 4),
(271, 2, 6, 5),
(361, 2, 6, 8),
(401, 2, 6, 9),
(37, 2, 7, 1),
(137, 2, 7, 2),
(192, 2, 7, 3),
(242, 2, 7, 4),
(272, 2, 7, 5),
(362, 2, 7, 8),
(402, 2, 7, 9),
(38, 2, 8, 1),
(138, 2, 8, 2),
(193, 2, 8, 3),
(243, 2, 8, 4),
(273, 2, 8, 5),
(363, 2, 8, 8),
(403, 2, 8, 9),
(39, 2, 9, 1),
(139, 2, 9, 2),
(194, 2, 9, 3),
(244, 2, 9, 4),
(274, 2, 9, 5),
(364, 2, 9, 8),
(404, 2, 9, 9),
(40, 2, 10, 1),
(140, 2, 10, 2),
(195, 2, 10, 3),
(245, 2, 10, 4),
(275, 2, 10, 5),
(365, 2, 10, 8),
(405, 2, 10, 9),
(41, 2, 11, 1),
(141, 2, 11, 2),
(196, 2, 11, 3),
(366, 2, 11, 8),
(42, 2, 12, 1),
(142, 2, 12, 2),
(197, 2, 12, 3),
(367, 2, 12, 8),
(43, 2, 13, 1),
(143, 2, 13, 2),
(198, 2, 13, 3),
(368, 2, 13, 8),
(44, 2, 14, 1),
(144, 2, 14, 2),
(199, 2, 14, 3),
(369, 2, 14, 8),
(45, 2, 15, 1),
(145, 2, 15, 2),
(200, 2, 15, 3),
(370, 2, 15, 8),
(46, 2, 16, 1),
(201, 2, 16, 3),
(47, 2, 17, 1),
(202, 2, 17, 3),
(48, 2, 18, 1),
(203, 2, 18, 3),
(49, 2, 19, 1),
(204, 2, 19, 3),
(50, 2, 20, 1),
(205, 2, 20, 3),
(51, 2, 21, 1),
(52, 2, 22, 1),
(53, 2, 23, 1),
(54, 2, 24, 1),
(55, 2, 25, 1),
(56, 2, 26, 1),
(57, 2, 27, 1),
(58, 2, 28, 1),
(59, 2, 29, 1),
(60, 2, 30, 1),
(61, 2, 31, 1),
(62, 2, 32, 1),
(63, 2, 33, 1),
(64, 2, 34, 1),
(65, 2, 35, 1),
(66, 2, 36, 1),
(67, 2, 37, 1),
(68, 2, 38, 1),
(69, 2, 39, 1),
(70, 2, 40, 1),
(71, 3, 1, 1),
(146, 3, 1, 2),
(206, 3, 1, 3),
(246, 3, 1, 4),
(276, 3, 1, 5),
(371, 3, 1, 8),
(406, 3, 1, 9),
(72, 3, 2, 1),
(147, 3, 2, 2),
(207, 3, 2, 3),
(247, 3, 2, 4),
(277, 3, 2, 5),
(372, 3, 2, 8),
(407, 3, 2, 9),
(73, 3, 3, 1),
(148, 3, 3, 2),
(208, 3, 3, 3),
(248, 3, 3, 4),
(278, 3, 3, 5),
(373, 3, 3, 8),
(408, 3, 3, 9),
(74, 3, 4, 1),
(149, 3, 4, 2),
(209, 3, 4, 3),
(249, 3, 4, 4),
(279, 3, 4, 5),
(374, 3, 4, 8),
(409, 3, 4, 9),
(75, 3, 5, 1),
(150, 3, 5, 2),
(210, 3, 5, 3),
(250, 3, 5, 4),
(280, 3, 5, 5),
(375, 3, 5, 8),
(410, 3, 5, 9),
(76, 3, 6, 1),
(151, 3, 6, 2),
(211, 3, 6, 3),
(251, 3, 6, 4),
(281, 3, 6, 5),
(376, 3, 6, 8),
(411, 3, 6, 9),
(77, 3, 7, 1),
(152, 3, 7, 2),
(212, 3, 7, 3),
(252, 3, 7, 4),
(282, 3, 7, 5),
(377, 3, 7, 8),
(412, 3, 7, 9),
(78, 3, 8, 1),
(153, 3, 8, 2),
(213, 3, 8, 3),
(253, 3, 8, 4),
(283, 3, 8, 5),
(378, 3, 8, 8),
(413, 3, 8, 9),
(79, 3, 9, 1),
(154, 3, 9, 2),
(214, 3, 9, 3),
(254, 3, 9, 4),
(284, 3, 9, 5),
(379, 3, 9, 8),
(414, 3, 9, 9),
(80, 3, 10, 1),
(155, 3, 10, 2),
(215, 3, 10, 3),
(255, 3, 10, 4),
(285, 3, 10, 5),
(380, 3, 10, 8),
(415, 3, 10, 9),
(81, 3, 11, 1),
(156, 3, 11, 2),
(216, 3, 11, 3),
(381, 3, 11, 8),
(82, 3, 12, 1),
(157, 3, 12, 2),
(217, 3, 12, 3),
(382, 3, 12, 8),
(83, 3, 13, 1),
(158, 3, 13, 2),
(218, 3, 13, 3),
(383, 3, 13, 8),
(84, 3, 14, 1),
(159, 3, 14, 2),
(219, 3, 14, 3),
(384, 3, 14, 8),
(85, 3, 15, 1),
(160, 3, 15, 2),
(220, 3, 15, 3),
(385, 3, 15, 8),
(86, 3, 16, 1),
(161, 3, 16, 2),
(221, 3, 16, 3),
(87, 3, 17, 1),
(162, 3, 17, 2),
(222, 3, 17, 3),
(88, 3, 18, 1),
(163, 3, 18, 2),
(223, 3, 18, 3),
(89, 3, 19, 1),
(164, 3, 19, 2),
(224, 3, 19, 3),
(90, 3, 20, 1),
(165, 3, 20, 2),
(225, 3, 20, 3),
(91, 3, 21, 1),
(92, 3, 22, 1),
(93, 3, 23, 1),
(94, 3, 24, 1),
(95, 3, 25, 1),
(96, 3, 26, 1),
(97, 3, 27, 1),
(98, 3, 28, 1),
(99, 3, 29, 1),
(100, 3, 30, 1),
(101, 3, 31, 1),
(102, 3, 32, 1),
(103, 3, 33, 1),
(104, 3, 34, 1),
(105, 3, 35, 1),
(106, 3, 36, 1),
(107, 3, 37, 1),
(108, 3, 38, 1),
(109, 3, 39, 1),
(110, 3, 40, 1),
(111, 3, 41, 1),
(112, 3, 42, 1),
(113, 3, 43, 1),
(114, 3, 44, 1),
(115, 3, 45, 1),
(116, 3, 46, 1),
(117, 3, 47, 1),
(118, 3, 48, 1),
(119, 3, 49, 1),
(120, 3, 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `osobe`
--

CREATE TABLE `osobe` (
  `id` int(10) UNSIGNED NOT NULL,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `datum_registracije` datetime DEFAULT current_timestamp(),
  `uloga` enum('administrator','korisnik') DEFAULT 'korisnik',
  `status` enum('aktivan','deaktiviran') DEFAULT 'aktivan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `osobe`
--

INSERT INTO `osobe` (`id`, `ime`, `prezime`, `email`, `lozinka`, `telefon`, `datum_registracije`, `uloga`, `status`) VALUES
(1, 'Tijana', 'Gitarić', 'tijananrt1023@gs.viser.edu.rs', '$2y$10$JTLhXYfUWlrmYxr9i.8oe.exXyterxxaLgbXqCevt7Zc/a89WjW7C', '0611234567', '2026-02-24 15:55:37', 'administrator', 'aktivan'),
(2, 'Petar', 'Peric', 'pera@gmail.com', '$2y$10$1jTv7OE9RXtB8ufoL5ryQOEHMohq1Q59.Vncx09uEcs3aOQFn/.IG', '6526265231', '2026-02-24 19:10:45', 'korisnik', 'aktivan');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `idAdrese` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`id`, `naziv`, `idAdrese`) VALUES
(1, 'Beogradska Arena', 1),
(2, 'Sava Centar - Plava Dvorana', 2),
(3, 'Spens', 3),
(4, 'Sportska hala', 4),
(5, 'Pan Teatar', 5),
(6, 'Karmakoma club', 6),
(7, 'Zappa Baza', 7),
(8, 'Narodno pozorište', 8),
(9, 'Akademija 28', 9);

-- --------------------------------------------------------

--
-- Table structure for table `stavkekupovine`
--

CREATE TABLE `stavkekupovine` (
  `id` int(10) UNSIGNED NOT NULL,
  `idKupovine` int(10) UNSIGNED NOT NULL,
  `idKarte` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stavkekupovine`
--

INSERT INTO `stavkekupovine` (`id`, `idKupovine`, `idKarte`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `vrste`
--

CREATE TABLE `vrste` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(50) NOT NULL,
  `idKategorije` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vrste`
--

INSERT INTO `vrste` (`id`, `naziv`, `idKategorije`) VALUES
(1, 'Pop', 1),
(2, 'Rock', 1),
(3, 'Dj', 1),
(4, 'Hard and Heavy', 1),
(5, 'Džez i bluz', 1),
(6, 'Klasična muzika', 1),
(7, 'Opera', 1),
(8, 'Elektro', 1),
(9, 'Narodna muzika', 1),
(10, 'Hip hop', 1),
(11, 'Balet', 2),
(12, 'Dečije predstave', 2),
(13, 'Komedije', 2),
(14, 'Mjuzikli', 2),
(15, 'One man show', 2),
(16, 'Stand up', 2),
(17, 'Predstava', 2),
(18, 'Košarka', 3),
(19, 'Vaterpolo', 3),
(20, 'Muzika', 4),
(21, 'Film', 4),
(22, 'Istorija', 5),
(23, 'Tura sa vodičem', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adrese`
--
ALTER TABLE `adrese`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dogadjaji`
--
ALTER TABLE `dogadjaji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSale` (`idSale`),
  ADD KEY `idVrste` (`idVrste`);

--
-- Indexes for table `karte`
--
ALTER TABLE `karte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idDogadjaja` (`idDogadjaja`,`idMesta`),
  ADD KEY `idMesta` (`idMesta`);

--
-- Indexes for table `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kupovine`
--
ALTER TABLE `kupovine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idKorisnika` (`idKorisnika`);

--
-- Indexes for table `mesta`
--
ALTER TABLE `mesta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `red` (`red`,`broj`,`idSale`),
  ADD KEY `idSale` (`idSale`);

--
-- Indexes for table `osobe`
--
ALTER TABLE `osobe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAdrese` (`idAdrese`);

--
-- Indexes for table `stavkekupovine`
--
ALTER TABLE `stavkekupovine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idKupovine` (`idKupovine`,`idKarte`),
  ADD KEY `idKarte` (`idKarte`);

--
-- Indexes for table `vrste`
--
ALTER TABLE `vrste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idKategorije` (`idKategorije`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adrese`
--
ALTER TABLE `adrese`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dogadjaji`
--
ALTER TABLE `dogadjaji`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `karte`
--
ALTER TABLE `karte`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategorije`
--
ALTER TABLE `kategorije`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kupovine`
--
ALTER TABLE `kupovine`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mesta`
--
ALTER TABLE `mesta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `osobe`
--
ALTER TABLE `osobe`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stavkekupovine`
--
ALTER TABLE `stavkekupovine`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vrste`
--
ALTER TABLE `vrste`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dogadjaji`
--
ALTER TABLE `dogadjaji`
  ADD CONSTRAINT `dogadjaji_ibfk_1` FOREIGN KEY (`idSale`) REFERENCES `sale` (`id`),
  ADD CONSTRAINT `dogadjaji_ibfk_2` FOREIGN KEY (`idVrste`) REFERENCES `vrste` (`id`);

--
-- Constraints for table `karte`
--
ALTER TABLE `karte`
  ADD CONSTRAINT `karte_ibfk_1` FOREIGN KEY (`idMesta`) REFERENCES `mesta` (`id`),
  ADD CONSTRAINT `karte_ibfk_2` FOREIGN KEY (`idDogadjaja`) REFERENCES `dogadjaji` (`id`);

--
-- Constraints for table `kupovine`
--
ALTER TABLE `kupovine`
  ADD CONSTRAINT `kupovine_ibfk_1` FOREIGN KEY (`idKorisnika`) REFERENCES `osobe` (`id`);

--
-- Constraints for table `mesta`
--
ALTER TABLE `mesta`
  ADD CONSTRAINT `mesta_ibfk_1` FOREIGN KEY (`idSale`) REFERENCES `sale` (`id`);

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`idAdrese`) REFERENCES `adrese` (`id`);

--
-- Constraints for table `stavkekupovine`
--
ALTER TABLE `stavkekupovine`
  ADD CONSTRAINT `stavkekupovine_ibfk_1` FOREIGN KEY (`idKupovine`) REFERENCES `kupovine` (`id`),
  ADD CONSTRAINT `stavkekupovine_ibfk_2` FOREIGN KEY (`idKarte`) REFERENCES `karte` (`id`);

--
-- Constraints for table `vrste`
--
ALTER TABLE `vrste`
  ADD CONSTRAINT `vrste_ibfk_1` FOREIGN KEY (`idKategorije`) REFERENCES `kategorije` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
