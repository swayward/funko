 DROP USER 'funkoselect'@'localhost';
 DROP USER 'funkowrite'@'localhost';
 GRANT select ON funko.* TO 'funkoselect'@'localhost' IDENTIFIED BY 'xxxxxxxxxxxxxxxxxxxxxx';
 GRANT select, insert, update, delete ON funko.* TO 'funkowrite'@'localhost' IDENTIFIED BY 'xxxxxxxxxxxxxxxxxxxxxx';
 
 FLUSH PRIVILEGES;
