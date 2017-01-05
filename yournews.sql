-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 05 jan 2017 om 19:07
-- Serverversie: 10.1.19-MariaDB
-- PHP-versie: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yournews`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `input_sites`
--

CREATE TABLE `input_sites` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Name of the website',
  `domain` varchar(255) NOT NULL COMMENT 'Domain name without http or www before it',
  `area_query` varchar(255) NOT NULL COMMENT 'DOM query for getting the area with the links we''re interested in'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `input_sites`
--

INSERT INTO `input_sites` (`id`, `category`, `name`, `domain`, `area_query`) VALUES
(1, 'general', 'NBC News', 'nbcnews.com', '//*[contains(@class, ''row js-top-stories-content'')]'),
(2, 'general', 'USA today', 'usatoday.com', '//*[contains(@id, ''section_home'')]'),
(3, 'general', 'Bloomberg', 'bloomberg.com', '//*[contains(@class, ''home__top-of-home'')]'),
(4, 'general', 'Fox news', 'foxnews.com', '//*[contains(@id, ''latest'')]'),
(6, 'tech', 'CNET', 'cnet.com', '//*[contains(@class, ''responsiveLatest'')]'),
(7, 'tech', 'Tech Crunch', 'techcrunch.com', '//*[contains(@id, ''river1'')]'),
(8, 'tech', 'Tech News World', 'technewsworld.com', '//*[contains(@id, ''content-main'')]'),
(9, 'politics', 'CNN', 'cnn.com', '//*[contains(@class, ''pg-no-rail pg-wrapper'')]'),
(10, 'politics', 'Huffington Post', 'huffingtonpost.com', '//*[@class=''a-page__content'']'),
(11, 'financial', 'Wall Street Journal', 'wsj.com', '//*[contains(@class, ''lead-story'')]'),
(12, 'financial', 'CNBC', 'cnbc.com', '//*[contains(@class, ''cnbc-body'')] '),
(13, 'financial', 'Reuters', 'reuters.com', '//*[contains(@id, ''rcs-mainContentTop'')]'),
(14, 'sports', 'ESPN', 'espn.com', '//*[contains(@class, ''container-wrapper'')]'),
(15, 'sports', 'Fox Sports', 'foxsports.com', '//*[contains(@class,''body-content'')]'),
(16, 'sports', 'NBC Sports', 'nbcsports.com', '//*[contains(@class,''main-layout'')]'),
(17, 'entertainment', 'E!', 'eonline.com', '//*[contains(@class,''rebrand-widget-container'')]'),
(18, 'entertainment', 'US magazine', 'usmagazine.com', '//*[contains(@class,''home-layout'')]'),
(19, 'entertainment', 'TMZ', 'tmz.com', '//*[contains(@id,''main-content'')]');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `input_sites`
--
ALTER TABLE `input_sites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `input_sites`
--
ALTER TABLE `input_sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
