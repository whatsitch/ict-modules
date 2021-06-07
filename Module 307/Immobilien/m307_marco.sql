-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 02. Mrz 2021 um 15:24
-- Server-Version: 5.7.24
-- PHP-Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `m307_marco`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `marco_immo`
--

CREATE TABLE `marco_immo` (
  `id` int(11) NOT NULL,
  `objekt` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `plz` int(11) NOT NULL,
  `kategorie` varchar(255) NOT NULL,
  `bemerkung` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `marco_immo`
--

INSERT INTO `marco_immo` (`id`, `objekt`, `adresse`, `plz`, `kategorie`, `bemerkung`, `status`, `added`) VALUES
(1, '4.5 Zimmer', 'Haupstrasse 45', 8000, 'Wohnung', NULL, 0, '2021-03-02 16:24:34'),
(2, 'MFH 5.5', 'Haupstrasse 46', 9000, 'Haus', NULL, 0, '2021-03-02 16:24:34'),
(3, 'Garage mit Zusatzraum', 'Haupstrasse 75', 3000, 'Objekt', NULL, 1, '2021-03-02 16:24:34');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `marco_immo`
--
ALTER TABLE `marco_immo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `marco_immo`
--
ALTER TABLE `marco_immo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
