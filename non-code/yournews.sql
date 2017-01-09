-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 09 jan 2017 om 14:12
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
-- Tabelstructuur voor tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `input_site` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp on which is was discovered by our crawler, not when it was published',
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `forbidden_links`
--

CREATE TABLE `forbidden_links` (
  `id` int(11) NOT NULL,
  `input_site` int(11) NOT NULL,
  `text` varchar(255) NOT NULL COMMENT 'If a link or title contains this text, it is skipped during the crawling'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `forbidden_links`
--

INSERT INTO `forbidden_links` (`id`, `input_site`, `text`) VALUES
(1, 1, '/news/us-news'),
(2, 1, '/news/weather'),
(3, 1, '/news/world'),
(4, 1, '/meet-the-press'),
(5, 2, '/media/latest/videos/home/'),
(6, 2, '/money/'),
(7, 2, '/sports/'),
(8, 2, 'puzzles.usatoday.com'),
(9, 2, 'games.usatoday.com'),
(10, 2, '/opinion/'),
(11, 2, '/tech/'),
(12, 2, '/travel/'),
(13, 2, 'http://www.reviewed.com/'),
(14, 3, 'facebook.com'),
(15, 3, 'twitter.com'),
(16, 3, '/sponsor'),
(17, 3, '/businessweek'),
(18, 3, '/hpsubtout'),
(19, 6, 'All News'),
(20, 6, '/profiles/'),
(21, 7, '/author/'),
(22, 7, '/artificial-intelligence-2/'),
(23, 7, '#'),
(24, 8, '/archives/'),
(25, 10, '/author/'),
(26, 12, 'nytimes.com'),
(27, 12, 'theverge.com'),
(31, 13, '/theWire'),
(32, 13, '/news/pictures'),
(33, 13, '/commentary'),
(34, 13, '/finance/personal-finance'),
(35, 14, 'nfl/team'),
(36, 14, '/video/'),
(37, 15, 'More News'),
(38, 15, '?pn='),
(39, 15, 'fsgo.com'),
(40, 15, 'foxbusiness.com'),
(41, 16, 'scores.nbcsports.com'),
(42, 16, '/video/'),
(43, 16, '/nascar'),
(44, 16, '/snf-all-access'),
(45, 16, '/player-news'),
(46, 16, 'eonline.com/news/'),
(47, 19, 'toofab.com'),
(48, 19, '/videos/'),
(49, 19, 'wwtdd.com'),
(50, 19, '/terms/'),
(51, 19, '/advertise'),
(52, 19, '/pr'),
(53, 19, 'youtube.com/user/TMZ'),
(54, 19, 'instagram.com/tmz_tv/'),
(55, 19, 'plus.google.com'),
(56, 19, 'itunes.apple.com'),
(57, 19, 'play.google.com'),
(58, 19, 'privacy-center-wb-privacy-policy'),
(59, 19, 'Jobs.aspx'),
(60, 19, '/tmzmobilealerts'),
(61, 19, '/about'),
(62, 19, 'elitedaily.com'),
(63, 9, '/staff/'),
(64, 9, '/magazine/'),
(67, 3, 'bloomberg.com/view'),
(68, 3, 'bloomberg.com/gadfly'),
(74, 7, '/contributor/'),
(75, 15, 'shop.foxsports.com'),
(76, 15, 'foxsports.com/undisputed'),
(77, 19, '/category/'),
(78, 19, '/tips'),
(79, 19, '/terms'),
(80, 19, '/contact/'),
(81, 1, 'advertisement'),
(82, 7, '/social/'),
(83, 12, '/the-weekly/'),
(84, 12, 'facebook.com'),
(85, 12, 'twitter.com'),
(86, 12, 'linkedin.com'),
(87, 12, 'youtube.com'),
(88, 12, 'data./'),
(89, 12, '/trader-poll/'),
(90, 19, '/page/'),
(91, 19, '/photos/');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `input_sites`
--

CREATE TABLE `input_sites` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Name of the website',
  `domain` varchar(255) NOT NULL COMMENT 'Domain name without http or www before it',
  `area_query` varchar(255) NOT NULL COMMENT 'DOM query for getting the area with the links we''re interested in',
  `article_area_query` varchar(255) NOT NULL COMMENT 'The DOM area query for retrieving the aritcle content'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `input_sites`
--

INSERT INTO `input_sites` (`id`, `category`, `name`, `domain`, `area_query`, `article_area_query`) VALUES
(1, 'general', 'NBC News', 'nbcnews.com', '//*[contains(@class, ''row js-top-stories-content'')]', '//*[contains(@class, ''article-body'')]'),
(2, 'general', 'USA today', 'usatoday.com', '//*[contains(@id, ''section_home'')]', '//*[contains(@itemprob, ''articleBody'')]'),
(3, 'general', 'Bloomberg', 'bloomberg.com', '//*[contains(@class, ''home__top-of-home'')]', '//*[contains(@class, ''body-copy'')]'),
(4, 'general', 'Fox news', 'foxnews.com', '//*[contains(@id, ''latest'')]', '//*[contains(@class, ''article-text'')]'),
(6, 'tech', 'CNET', 'cnet.com', '//*[contains(@class, ''responsiveLatest'')]', '//*[contains(@class, ''article-main-body'')]'),
(7, 'tech', 'Tech Crunch', 'techcrunch.com', '//*[contains(@id, ''river1'')]', '//*[contains(@class, ''article-entry'')]'),
(8, 'tech', 'Tech News World', 'technewsworld.com', '//*[contains(@id, ''content-main'')]', '//*[contains(@id, ''story-body'')]'),
(9, 'politics', 'Politico', 'politico.com', '//*[contains(@class, ''cluster-groupset layout-grid grid-4'')]', '//*[contains(@class, ''story-text'')]'),
(10, 'politics', 'Huffington Post', 'huffingtonpost.com', '//*[@class=''a-page__content'']', '//*[contains(@class, ''entry__body js-entry-body'')]'),
(11, 'financial', 'Wall Street Journal', 'wsj.com', '//*[contains(@class, ''lead-story'')]', '//*[contains(@class, ''wsj-snippet-body'')]'),
(12, 'financial', 'CNBC', 'cnbc.com', '//*[contains(@class, ''cnbc-body'')] ', '//*[contains(@class, ''group-container last'')]'),
(13, 'financial', 'Reuters', 'reuters.com', '//*[contains(@id, ''rcs-mainContentTop'')]', '//*[contains(@id, ''article-text'')]'),
(14, 'sports', 'ESPN', 'espn.com', '//*[contains(@class, ''container-wrapper'')]', '//*[contains(@class, ''article-body'')]'),
(15, 'sports', 'Fox Sports', 'foxsports.com', '//*[contains(@class,''body-content'')]', '//*[contains(@itemprob, ''articleBody'')]'),
(16, 'sports', 'NBC Sports', 'nbcsports.com', '//*[contains(@class,''main-layout'')]', '//*[contains(@class, ''post-body'')]'),
(17, 'entertainment', 'E!', 'eonline.com', '//*[contains(@class,''js-categorygrid--'')]', '//*[contains(@id, ''article-detail'')]'),
(18, 'entertainment', 'US magazine', 'usmagazine.com', '//*[contains(@class,''home-layout'')]', '//*[contains(@class, ''article-body-inner'')]'),
(19, 'entertainment', 'TMZ', 'tmz.com', '//*[contains(@id,''main-content'')]', '//*[contains(@class, ''article-content'')]');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `input_site` (`input_site`);

--
-- Indexen voor tabel `forbidden_links`
--
ALTER TABLE `forbidden_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `input_site` (`input_site`);

--
-- Indexen voor tabel `input_sites`
--
ALTER TABLE `input_sites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=618;
--
-- AUTO_INCREMENT voor een tabel `forbidden_links`
--
ALTER TABLE `forbidden_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT voor een tabel `input_sites`
--
ALTER TABLE `input_sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`input_site`) REFERENCES `input_sites` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `forbidden_links`
--
ALTER TABLE `forbidden_links`
  ADD CONSTRAINT `forbidden_links_ibfk_1` FOREIGN KEY (`input_site`) REFERENCES `input_sites` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
