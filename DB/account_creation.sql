CREATE TABLE `account_creation`.`account_users` ( 
    `ID` INT NOT NULL AUTO_INCREMENT, 
    `Username` VARCHAR(50) NOT NULL, 
    `Password` VARCHAR(150) NOT NULL, 
    `DateCreated` DATETIME NOT NULL, 
    PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;