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

CREATE TABLE IF NOT EXISTS `bilder` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(50) default 'n/a',
  `Titel` varchar(255) default NULL,
  `Dateityp` varchar(10) NOT NULL,
  `Größe` decimal(10,0) NOT NULL,
  `Höhe` int(11) NOT NULL,
  `Breite` int(11) NOT NULL,
  `Pfad` varchar(255) NOT NULL,
  `Hash` varchar(35) NOT NULL,
  `Bewertung` int(11) default '0',
  `Bewertung_Anz` int(11) default '0',
  `Bild_Datum` datetime default NULL,
  `Autor_ID` int(11) default NULL,
  `Ordner_ID` int(11) default NULL,
  `Thumbnail_Pfad` varchar(255) NOT NULL,
  `Ordner_SubID` int(11) unsigned default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Hash` (`Hash`),
  KEY `Autor_ID` (`Autor_ID`),
  KEY `Thumbnail_ID` (`Thumbnail_Pfad`),
  KEY `Ordner_ID` (`Ordner_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Bildertabelle';

--
-- Daten für Tabelle `bilder`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommentare`
--

CREATE TABLE IF NOT EXISTS `kommentare` (
  `ID` int(11) NOT NULL auto_increment,
  `Titel` varchar(50) NOT NULL,
  `Text` varchar(50) NOT NULL,
  `Autor_ID` int(11) default NULL,
  `Datum` datetime default NULL,
  `Bild_ID` int(11) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `Bild_ID` (`Bild_ID`),
  KEY `Autor_ID` (`Autor_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Kommentar-Tabelle';

--
-- Daten für Tabelle `kommentare`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ordner`
--

CREATE TABLE IF NOT EXISTS `ordner` (
  `ID` int(11) NOT NULL auto_increment,
  `Name` varchar(50) NOT NULL,
  `Pfad` varchar(255) NOT NULL,
  `Aufnahme_Datum` date default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Ordner-Tabelle';

--
-- Daten für Tabelle `ordner`
--


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


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL auto_increment,
  `Vorname` varchar(45) default NULL,
  `Nachname` varchar(45) default NULL,
  `Nick` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Gebdatum` date default NULL,
  `ICQ` int(11) default NULL,
  `MSN` varchar(45) default NULL,
  `Bild_ID` int(11) default NULL,
  `Passwort` varchar(45) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `Bild_ID` (`Bild_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='User-Tabelle';

--
-- Daten für Tabelle `users`
--

