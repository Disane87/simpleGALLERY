-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 12. März 2009 um 12:38
-- Server Version: 5.0.67
-- PHP-Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `simplegallery`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

ALTER TABLE `bilder` ADD `Ordner_SubID` INT NULL AFTER `Ordner_ID` ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ordner_sub`
--

CREATE TABLE IF NOT EXISTS `ordner_sub` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `ParentID` int(10) unsigned NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Pfad` varchar(45) NOT NULL,
  `Aufnahme_Datum` date NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Unterordner';

--
-- Daten für Tabelle `ordner_sub`
--

DROP TABLE thumbnails;


-- --------------------------------------------------------

