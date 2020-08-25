/* MIGRATE TO VERSION 2*/

ALTER TABLE `rating_summary` ADD `property` varchar(50) NOT NULL
INSERT INTO `rating_settings` (property, value) VALUES ('version','2.0');


/* SQL Server / MS Access  */
ALTER TABLE rating_banlist ALTER COLUMN `ip` varchar(50)
ALTER TABLE rating_details ALTER COLUMN `ip` varchar(50) 
ALTER TABLE rating_details ALTER COLUMN `rate` decimal(8,5)
ALTER TABLE rating_summary ALTER COLUMN `mean` decimal(8,5)


/* My SQL / Oracle (prior version 10G)   */
ALTER TABLE rating_banlist MODIFY COLUMN `ip` varchar(50)
ALTER TABLE rating_details MODIFY COLUMN `ip` varchar(50)
ALTER TABLE rating_details MODIFY COLUMN `rate` decimal(8,5)
ALTER TABLE rating_summary MODIFY COLUMN `mean` decimal(8,5)


/*Oracle 10G and later */
ALTER TABLE rating_banlist MODIFY `ip` varchar(50)		
ALTER TABLE rating_details MODIFY `ip` varchar(50)
ALTER TABLE rating_details MODIFY `rate` decimal(8,5)
ALTER TABLE rating_summary MODIFY `mean` decimal(8,5)


