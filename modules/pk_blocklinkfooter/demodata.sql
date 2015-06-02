
--
-- Table structure for table `ps_blocklinkfooter`
--

DROP TABLE IF EXISTS `ps_blocklinkfooter`;
CREATE TABLE IF NOT EXISTS `ps_blocklinkfooter` (
  `id_blocklinkfooter` int(2) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `new_window` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_blocklinkfooter`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ps_blocklinkfooter`
--

INSERT INTO `ps_blocklinkfooter` (`id_blocklinkfooter`, `url`, `new_window`) VALUES
(1, 'index.php?id_cms=4&controller=cms', 0),
(2, 'index.php?id_cms=1&controller=cms', 0),
(3, 'index.php?id_cms=2&controller=cms', 0),
(4, 'index.php?id_cms=5&controller=cms', 0),
(8, 'index.php?id_cms=3&controller=cms', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ps_blocklinkfooter_lang`
--

DROP TABLE IF EXISTS `ps_blocklinkfooter_lang`;
CREATE TABLE IF NOT EXISTS `ps_blocklinkfooter_lang` (
  `id_blocklinkfooter` int(2) NOT NULL,
  `id_lang` int(2) NOT NULL,
  `text` varchar(64) NOT NULL,
  PRIMARY KEY (`id_blocklinkfooter`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ps_blocklinkfooter_lang`
--

INSERT INTO `ps_blocklinkfooter_lang` (`id_blocklinkfooter`, `id_lang`, `text`) VALUES
(1, 1, 'About Us'),
(1, 2, 'Wir über uns'),
(1, 3, 'Acerca de nosotros'),
(1, 4, 'A propos de nous'),
(1, 5, 'Chi siamo'),
(2, 1, 'Delivery'),
(2, 2, 'Lieferung'),
(2, 3, 'entrega'),
(2, 4, 'livraison'),
(2, 5, 'consegna'),
(3, 1, 'Legal Notice'),
(3, 2, 'Rechtliche Hinweise'),
(3, 3, 'Aviso legal'),
(3, 4, 'Mentions légales'),
(3, 5, 'Note legali'),
(4, 1, 'Secure Payment'),
(4, 2, 'Sichere Bezahlung'),
(4, 3, 'pago Seguro'),
(4, 4, 'Paiement sécurisé'),
(4, 5, 'pagamento sicuro'),
(8, 1, 'Terms & conditions of use'),
(8, 2, 'Allgemeine Geschäftsbedingungen'),
(8, 3, 'Términos y condiciones'),
(8, 4, 'Conditions générales de vente'),
(8, 5, 'Termini e condizioni');

-- --------------------------------------------------------

--
-- Table structure for table `ps_blocklinkfooter_shop`
--

DROP TABLE IF EXISTS `ps_blocklinkfooter_shop`;
CREATE TABLE IF NOT EXISTS `ps_blocklinkfooter_shop` (
  `id_blocklinkfooter` int(2) NOT NULL AUTO_INCREMENT,
  `id_shop` varchar(255) NOT NULL,
  PRIMARY KEY (`id_blocklinkfooter`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;