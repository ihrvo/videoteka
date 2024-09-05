DROP DATABASE IF EXISTS `videoteka`;

CREATE DATABASE IF NOT EXISTS `videoteka` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `videoteka`;

DELIMITER $$

CREATE PROCEDURE `create_posudba`(
    IN p_clan_id INT UNSIGNED,
    IN p_kopija_id INT UNSIGNED
)
BEGIN
    DECLARE v_posudba_id INT UNSIGNED;
    DECLARE v_kolicina INT;

    START TRANSACTION;

    -- Ensure the film copy is available
    SELECT COUNT(k.id)
        INTO v_kolicina
        FROM kopija k
        JOIN kopija k2 ON k.film_id = k2.film_id AND k.medij_id = k2.medij_id
        WHERE k.dostupan = 1
        AND k2.id = p_kopija_id
        FOR UPDATE;

    IF v_kolicina > 0 THEN
        -- Insert the new borrowing record
        INSERT INTO posudba (datum_posudbe, clan_id)
        VALUES (CURDATE(), p_clan_id);

        SET v_posudba_id = LAST_INSERT_ID();

        -- Insert the kopija into posudba_kopija
        INSERT INTO posudba_kopija (posudba_id, kopija_id)
        VALUES (v_posudba_id, p_kopija_id);

        -- Update the availability of the kopija
        UPDATE kopija
        SET dostupan = 0
        WHERE id = p_kopija_id;

        COMMIT;
    ELSE
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock available';
    END IF;
END $$

DELIMITER ;


CREATE TABLE IF NOT EXISTS `zanrovi` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ime` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ime` (`ime`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `zanrovi` (`id`, `ime`) VALUES
(1, 'Akcija'),
(9, 'Dokumentarni'),
(3, 'Drama'),
(8, 'Fantazija'),
(4, 'Horor'),
(2, 'Komedija'),
(5, 'Romantika'),
(6, 'Sci-Fi'),
(7, 'Triler');


CREATE TABLE IF NOT EXISTS `cjenik` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tip_filma` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `cijena` decimal(10,2) NOT NULL,
  `zakasnina_po_danu` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip_filma` (`tip_filma`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cjenik` (`id`, `tip_filma`, `cijena`, `zakasnina_po_danu`) VALUES
(1, 'Hit', 5.00, 1.00),
(2, 'Ne-hit', 3.00, 0.50),
(3, 'Stari', 1.50, 0.25);


CREATE TABLE IF NOT EXISTS `mediji` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tip` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `koeficijent` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip` (`tip`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mediji` (`id`, `tip`, `koeficijent`) VALUES
(1, 'DVD', 1),
(2, 'Blu-ray', 1.5),
(3, 'VHS', 0.8);


CREATE TABLE IF NOT EXISTS `clanovi` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ime` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prezime` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `adresa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefon` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `clanski_broj` char(14) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `clanski_broj` (`clanski_broj`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `clanovi` (`ime`, `prezime`, `adresa`, `telefon`, `email`, `clanski_broj`) VALUES
('Ivan', 'Horvat', 'Vukovarska 202', '0912345678', 'ivan.horvat@example.com', 'CLAN12345'),
('Ana', 'Kovač', 'Ulica Matije Gupca 15', '0912345679', 'ana.kovac@example.com', 'CLAN12346'),
('Marko', 'Maric', 'Ulica Ivana Gundulića 5', '0912345680', 'marko.maric@example.com', 'CLAN12347'),
('Petra', 'Novak', 'Ulica Stjepana Radića 8', '0912345681', 'petra.novak@example.com', 'CLAN12348'),
('Lucija', 'Jurić', 'Ulica bana Jelačića 7', '0912345682', 'lucija.juric@example.com', 'CLAN12349'),
('Ivan', 'Perić', 'Trg kralja Tomislava 3', '0912345683', 'ivan.peric@example.com', 'CLAN12350'),
('Maja', 'Božić', 'Ulica Josipa Broza 9', '0912345684', 'maja.bozic@example.com', 'CLAN12351'),
('Nikola', 'Kovačević', 'Ulica Ante Starčevića 6', '0912345685', 'nikola.kovacevic@example.com', 'CLAN12352'),
('Ivana', 'Matić', 'Ulica Josipa Jurja Strossmayera 2', '0912345686', 'ivana.matic@example.com', 'CLAN12353'),
('Marin', 'Babić', 'Ulica Ljudevita Gaja 10', '0912345687', 'marin.babic@example.com', 'CLAN12354'),
('Katarina', 'Petrović', 'Ulica Ivana Mažuranića 4', '0912345688', 'katarina.petrovic@example.com', 'CLAN12355'),
('Tomislav', 'Radić', 'Ulica Pavla Šubića 12', '0912345689', 'tomislav.radic@example.com', 'CLAN12356'),
('Doris', 'Grgić', 'Trg bana Josipa Jelačića 11', '0912345690', 'doris.grgic@example.com', 'CLAN12357'),
('Josip', 'Lovrić', 'Ulica Michaela Scumachera 3', '0912345691', 'josip.lovric@example.com', 'CLAN12358'),
('Marta', 'Pavlović', 'Ulica Vladimira Nazora 8', '0912345692', 'marta.pavlovic@example.com', 'CLAN12359');


CREATE TABLE IF NOT EXISTS `filmovi` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `naslov` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `godina` char(4) COLLATE utf8mb4_general_ci NOT NULL,
  `zanr_id` int UNSIGNED NOT NULL,
  `cjenik_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`zanr_id`) REFERENCES zanrovi(id),
  FOREIGN KEY (`cjenik_id`) REFERENCES cjenik(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `filmovi` (`naslov`, `godina`, `zanr_id`, `cjenik_id`) VALUES
('Inception', '2010', 1, 1),
('Kum', '1972', 3, 2),
('Ralje', '1975', 4, 2),
('Titanic', '1997', 5, 3),
('Matrix', '1999', 1, 1),
('Deadpool 2', '2018', 2, 2),
('Interstellar', '2014', 6, 1),
('The Shawshank Redemption', '1994', 3, 2),
('The Godfather Part II', '1974', 3, 2),
('The Dark Knight', '2008', 1, 1),
('Pulp Fiction', '1994', 7, 2),
('Schindlers List', '1993', 3, 3),
('Forrest Gump', '1994', 2, 2),
('The Lord of the Rings: The Fellowship of the Ring', '2001', 8, 1),
('Fight Club', '1999', 7, 2),
('The Lord of the Rings: The Return of the King', '2003', 8, 1),
('The Good, the Bad and the Ugly', '1966', 7, 3),
('The Lord of the Rings: The Two Towers', '2002', 8, 1),
('Star Wars: Episode IV - A New Hope', '1977', 6, 3),
('Inglourious Basterds', '2009', 1, 2),
('Saving Private Ryan', '1998', 1, 1),
('The Prestige', '2006', 7, 1),
('The Green Mile', '1999', 3, 2),
('Gladiator', '2000', 1, 1),
('The Lion King', '1994', 8, 3),
('Se7en', '1995', 7, 2),
('The Silence of the Lambs', '1991', 7, 3),
('Avatar', '2009', 8, 1),
('Django Unchained', '2012', 1, 2),
('The Departed', '2006', 7, 2),
('Memento', '2000', 7, 1),
('Braveheart', '1995', 1, 3),
('Casablanca', '1942', 5, 3),
('Whiplash', '2014', 3, 2),
('The Intouchables', '2011', 2, 3),
('Goodfellas', '1990', 7, 2),
('The Pianist', '2002', 3, 2),
('The Usual Suspects', '1995', 7, 2),
('Psycho', '1960', 4, 3),
('American History X', '1998', 3, 2),
('Mad Max: Fury Road', '2015', 1, 1),
('The Shining', '1980', 4, 3),
('WALL·E', '2008', 2, 3),
('The Truman Show', '1998', 7, 2),
('Blade Runner 2049', '2017', 6, 1),
('Back to the Future', '1985', 6, 3),
('Alien', '1979', 6, 3),
('The Grand Budapest Hotel', '2014', 2, 2),
('The Wolf of Wall Street', '2013', 2, 2),
('Parasite', '2019', 7, 2),
('Coco', '2017', 8, 3),
('1917', '2019', 1, 2),
('Joker', '2019', 7, 1),
('Spirited Away', '2001', 8, 3),
('The Social Network', '2010', 9, 2),
('Once Upon a Time in Hollywood', '2019', 1, 2);


CREATE TABLE IF NOT EXISTS `kopija` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `barcode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dostupan` tinyint(1) DEFAULT '1',
  `film_id` int UNSIGNED NOT NULL,
  `medij_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`barcode`),
  FOREIGN KEY (`film_id`) REFERENCES filmovi(id),
  FOREIGN KEY (`medij_id`) REFERENCES mediji(id)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kopija` (`barcode`, `dostupan`, `film_id`, `medij_id`) VALUES
('INCEPTDVD', 0, 1, 1),
('INCEPTDVD', 1, 1, 1),
('INCEPTDVD', 1, 1, 1),
('INCEPTBLURAY', 0, 1, 2),
('INCEPTBLURAY', 0, 1, 2),
('INCEPTVHS', 0, 1, 3),
('INCEPTVHS', 1, 1, 3),
('KUMDVD', 1, 2, 1),
('KUMDVD', 0, 2, 1),
('KUMBLURAY', 1, 2, 2),
('KUMBLURAY', 0, 2, 2),
('RALJEDVD', 0, 3, 1),
('RALJEBR', 1, 3, 2),
('RALJEBR', 1, 3, 2),
('RALJEVHS', 0, 3, 3),
('RALJEVHS', 1, 3, 3),
('RALJEVHS', 0, 3, 3),
('TITANICDVD', 1, 4, 1),
('TITANICDVD', 1, 4, 1),
('TITANICDVD', 1, 4, 1),
('TITANICDVD', 1, 4, 1),
('TITANICBR', 1, 4, 2),
('TITANICBR', 1, 4, 2),
('MATRIXDVD', 1, 5, 1),
('MATRIXDVD', 1, 5, 1),
('MATRIXBR', 1, 5, 2),
('DEAD2DVD', 1, 6, 1),
('DEAD2DVD', 1, 6, 1),
('DEAD2DVD', 1, 6, 1),
('DEAD2DVD', 1, 6, 1),
('DEAD2BLURAY', 1, 6, 2),
('DEAD2BLURAY', 1, 6, 2),
('INTERSTELLARDVD', 0, 7, 1),
('INTERSTELLARBLURAY', 1, 7, 2),
('INTERSTELLARVHS', 1, 7, 3),
('SHAWSHANKDVD', 1, 8, 1),
('SHAWSHANKBLURAY', 1, 8, 2),
('SHAWSHANKVHS', 1, 8, 3),
('GODFATHERIIDVD', 1, 9, 1),
('GODFATHERIIBLURAY', 1, 9, 2),
('GODFATHERIIVHS', 1, 9, 3),
('DARKKNIGHTDVD', 1, 10, 1),
('DARKKNIGHTBLURAY', 1, 10, 2),
('DARKKNIGHTVHS', 1, 10, 3),
('PULPFICTIONDVD', 1, 11, 1),
('PULPFICTIONBLURAY', 0, 11, 2),
('PULPFICTIONVHS', 1, 11, 3),
('FORRESTGUMP_DVD1', 1, 13, 1),
('FORRESTGUMP_BLURAY1', 1, 13, 2),
('FORRESTGUMP_VHS1', 1, 13, 3),
('LOTRFOTR_DVD1', 1, 14, 1),
('LOTRFOTR_BLURAY1', 1, 14, 2),
('LOTRFOTR_VHS1', 1, 14, 3),
('FIGHTCLUB_DVD1', 1, 15, 1),
('FIGHTCLUB_BLURAY1', 1, 15, 2),
('FIGHTCLUB_VHS1', 1, 15, 3),
('LOTRROTK_DVD1', 1, 16, 1),
('LOTRROTK_BLURAY1', 1, 16, 2),
('LOTRROTK_VHS1', 1, 16, 3),
('GOODBADUGLY_DVD1', 1, 17, 1),
('GOODBADUGLY_BLURAY1', 1, 17, 2),
('GOODBADUGLY_VHS1', 1, 17, 3),
('LOTRTTT_DVD1', 1, 18, 1),
('LOTRTTT_BLURAY1', 1, 18, 2),
('LOTRTTT_VHS1', 1, 18, 3),
('STARWARS4_DVD1', 0, 19, 1),
('STARWARS4_BLURAY1', 1, 19, 2),
('STARWARS4_VHS1', 1, 19, 3),
('INGLOURIOUS_DVD1', 1, 20, 1),
('INGLOURIOUS_BLURAY1', 1, 20, 2),
('INGLOURIOUS_VHS1', 1, 20, 3),
('SAVINGPRIVATE_DVD1', 1, 21, 1),
('SAVINGPRIVATE_BLURAY1', 1, 21, 2),
('SAVINGPRIVATE_VHS1', 1, 21, 3),
('PRESTIGE_DVD1', 1, 22, 1),
('PRESTIGE_BLURAY1', 1, 22, 2),
('PRESTIGE_VHS1', 1, 22, 3),
('GREENMILE_DVD1', 1, 23, 1),
('GREENMILE_BLURAY1', 1, 23, 2),
('GREENMILE_VHS1', 1, 23, 3),
('GLADIATOR_DVD1', 1, 24, 1),
('GLADIATOR_BLURAY1', 1, 24, 2),
('GLADIATOR_VHS1', 1, 24, 3),
('LIONKING_DVD1', 1, 25, 1),
('LIONKING_BLURAY1', 1, 25, 2),
('LIONKING_VHS1', 1, 25, 3),
('SE7EN_DVD1', 1, 26, 1),
('SE7EN_BLURAY1', 1, 26, 2),
('SE7EN_VHS1', 1, 26, 3),
('SILENCE_LAMBS_DVD1', 1, 27, 1),
('SILENCE_LAMBS_BLURAY1', 1, 27, 2),
('SILENCE_LAMBS_VHS1', 1, 27, 3),
('AVATAR_DVD1', 1, 28, 1),
('AVATAR_BLURAY1', 1, 28, 2),
('AVATAR_VHS1', 1, 28, 3),
('DJANGO_DVD1', 1, 29, 1),
('DJANGO_BLURAY1', 1, 29, 2),
('DJANGO_VHS1', 1, 29, 3),
('DEPARTED_DVD1', 1, 30, 1),
('DEPARTED_BLURAY1', 1, 30, 2),
('DEPARTED_VHS1', 1, 30, 3),
('MEMENTO_DVD1', 1, 31, 1),
('MEMENTO_BLURAY1', 1, 31, 2),
('MEMENTO_VHS1', 1, 31, 3),
('BRAVEHEART_DVD1', 1, 32, 1),
('BRAVEHEART_BLURAY1', 1, 32, 2),
('BRAVEHEART_VHS1', 1, 32, 3),
('CASABLANCA_DVD1', 1, 33, 1),
('CASABLANCA_BLURAY1', 1, 33, 2),
('CASABLANCA_VHS1', 1, 33, 3),
('WHIPLASH_DVD1', 1, 34, 1),
('WHIPLASH_BLURAY1', 1, 34, 2),
('WHIPLASH_VHS1', 1, 34, 3),
('INTOUCHABLES_DVD1', 1, 35, 1),
('INTOUCHABLES_BLURAY1', 1, 35, 2),
('INTOUCHABLES_VHS1', 1, 35, 3),
('GOODFELLAS_DVD1', 1, 36, 1),
('GOODFELLAS_BLURAY1', 1, 36, 2),
('GOODFELLAS_VHS1', 1, 36, 3),
('PIANIST_DVD1', 1, 37, 1),
('PIANIST_BLURAY1', 1, 37, 2),
('PIANIST_VHS1', 1, 37, 3),
('USUALSUSPECTS_DVD1', 1, 38, 1),
('USUALSUSPECTS_BLURAY1', 1, 38, 2),
('USUALSUSPECTS_VHS1', 1, 38, 3),
('PSYCHO_DVD1', 1, 39, 1),
('PSYCHO_BLURAY1', 1, 39, 2),
('PSYCHO_VHS1', 1, 39, 3),
('AMERICANHISTORYX_DVD1', 1, 40, 1),
('AMERICANHISTORYX_BLURAY1', 1, 40, 2),
('AMERICANHISTORYX_VHS1', 1, 40, 3),
('MADMAXFURYROAD_DVD1', 1, 41, 1),
('MADMAXFURYROAD_BLURAY1', 1, 41, 2),
('MADMAXFURYROAD_VHS1', 1, 41, 3),
('SHINING_DVD1', 1, 42, 1),
('SHINING_BLURAY1', 1, 42, 2),
('SHINING_VHS1', 1, 42, 3),
('WALLE_DVD1', 1, 43, 1),
('WALLE_BLURAY1', 1, 43, 2),
('WALLE_VHS1', 1, 43, 3),
('TRUMANSHOW_DVD1', 1, 44, 1),
('TRUMANSHOW_BLURAY1', 1, 44, 2),
('TRUMANSHOW_VHS1', 1, 44, 3),
('BLADERUNNER2049_DVD1', 1, 45, 1),
('BLADERUNNER2049_BLURAY1', 1, 45, 2),
('BLADERUNNER2049_VHS1', 1, 45, 3),
('BACKFUTURE_DVD1', 1, 46, 1),
('BACKFUTURE_BLURAY1', 1, 46, 2),
('BACKFUTURE_VHS1', 1, 46, 3),
('ALIEN_DVD1', 1, 47, 1),
('ALIEN_BLURAY1', 1, 47, 2),
('ALIEN_VHS1', 1, 47, 3),
('GRANDBUDAPEST_DVD1', 1, 48, 1),
('GRANDBUDAPEST_BLURAY1', 1, 48, 2),
('GRANDBUDAPEST_VHS1', 1, 48, 3),
('WOLFWALLSTREET_DVD1', 1, 49, 1),
('WOLFWALLSTREET_BLURAY1', 1, 49, 2),
('WOLFWALLSTREET_VHS1', 1, 49, 3),
('PARASITE_DVD1', 1, 50, 1),
('PARASITE_BLURAY1', 1, 50, 2),
('PARASITE_VHS1', 1, 50, 3),
('COCO_DVD1', 1, 51, 1),
('COCO_BLURAY1', 1, 51, 2),
('COCO_VHS1', 1, 51, 3),
('NINETEENSEVENTEEN_DVD1', 1, 52, 1),
('NINETEENSEVENTEEN_BLURAY1', 1, 52, 2),
('NINETEENSEVENTEEN_VHS1', 1, 52, 3),
('JOKER_DVD1', 1, 53, 1),
('JOKER_BLURAY1', 1, 53, 2),
('JOKER_VHS1', 1, 53, 3),
('SPIRITEDAWAY_DVD1', 1, 54, 1),
('SPIRITEDAWAY_BLURAY1', 1, 54, 2),
('SPIRITEDAWAY_VHS1', 1, 54, 3),
('SOCIALNETWORK_DVD1', 1, 55, 1),
('SOCIALNETWORK_BLURAY1', 1, 55, 2),
('SOCIALNETWORK_VHS1', 1, 55, 3),
('ONCEUPONTIMEHOLLYWOOD_DVD1', 1, 56, 1),
('ONCEUPONTIMEHOLLYWOOD_BLURAY1', 1, 56, 2),
('ONCEUPONTIMEHOLLYWOOD_VHS1', 1, 56, 3);


CREATE TABLE IF NOT EXISTS `posudba` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `datum_posudbe` date NOT NULL,
  `datum_povrata` date DEFAULT NULL,
  `clan_id` int UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`clan_id`) REFERENCES clanovi(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `posudba` (`id`, `datum_posudbe`, `datum_povrata`, `clan_id`, `updated_at`) VALUES
(1, '2024-06-09', '2024-06-13', 1, '2024-06-25 16:09:09'),
(2, '2024-06-12', '2024-06-13', 2, '2024-06-25 16:09:09'),
(3, '2024-06-12', '2024-06-15', 3, '2024-06-25 16:09:09'),
(4, '2024-06-15', '2024-06-17', 4, '2024-06-25 16:09:09'),
(5, '2024-06-25', NULL, 4, '2024-06-25 16:43:48'),
(6, '2024-06-25', NULL, 4, '2024-06-25 16:47:33'),
(7, '2024-06-25', NULL, 4, '2024-06-25 16:52:18'),
(8, '2024-07-03', NULL, 8, '2024-07-03 08:08:26'),
(9, '2024-07-03', NULL, 8, '2024-07-03 08:09:01'),
(10, '2024-07-03', NULL, 11, '2024-07-03 08:49:27');


CREATE TABLE IF NOT EXISTS `posudba_kopija` (
  `posudba_id` int UNSIGNED NOT NULL,
  `kopija_id` int UNSIGNED NOT NULL,
  FOREIGN KEY (posudba_id) REFERENCES posudba(id),
  FOREIGN KEY (kopija_id) REFERENCES kopija(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `posudba_kopija` (`posudba_id`, `kopija_id`) VALUES
(1, 1),
(1, 12),
(2, 6),
(2, 15),
(3, 4),
(4, 11),
(5, 17),
(6, 9),
(7, 5),
(8, 70),
(9, 90),
(10, 57);

