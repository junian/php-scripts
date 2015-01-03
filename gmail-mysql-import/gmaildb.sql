CREATE DATABASE IF NOT EXISTS `gmaildb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gmaildb`;

DROP TABLE IF EXISTS `emails`;
CREATE TABLE IF NOT EXISTS `emails` (
`id` int(11) NOT NULL,
  `mail_id` varchar(255) NOT NULL,
  `mail_date` varchar(255) NOT NULL,
  `to_addr` varchar(255) NOT NULL,
  `from_addr` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `mail_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `emails`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `mail_id` (`mail_id`);

ALTER TABLE `emails`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
