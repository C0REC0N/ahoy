CREATE DATABASE Ahoy;

CREATE TABLE `user` (
	`userID` INT(10) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`pass` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`roleChoice` ENUM('student','teacher','manager','employee') NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`userID`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;


CREATE TABLE `team` (
	`teamID` INT(10) NOT NULL,
	`userID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`teamID`) USING BTREE,
	INDEX `FK_team_user` (`userID`) USING BTREE,
	CONSTRAINT `FK_team_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;


CREATE TABLE teamShip(
    userGroupID int AUTO_INCREMENT,
    groupID int,
    userID int,
    PRIMARY KEY(userGroupID),
    FOREIGN KEY (userID) REFERENCES user(userID)
    FOREIGN KEY (teamID) REFERENCES Team(teamID)
);

CREATE TABLE `Ship` (
	`shipID` INT(10) NOT NULL AUTO_INCREMENT,
	`shipName` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`shipDescription` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`shipID`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;

CREATE TABLE `workspace` (
	`workspaceID` INT(10) NOT NULL AUTO_INCREMENT,
	`shipID` INT(10) NULL DEFAULT NULL,
	`fileName` MEDIUMBLOB NULL DEFAULT NULL,
	PRIMARY KEY (`workspaceID`) USING BTREE,
	INDEX `FK__ship` (`shipID`) USING BTREE,
	CONSTRAINT `FK__ship` FOREIGN KEY (`shipID`) REFERENCES `Ship` (`shipID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;


CREATE TABLE ChatMessages( 
    ChatID int AUTO_INCREMENT, 
    shipID int, 
    sender INT(10), 
    reciever VARCHAR(255), 
    chatContent VARCHAR(255), 
    timeSent DATETIME(fsp) NOT NULL DEFAULT current_timestamp(),
	timeRead DATETIME(fsp) DEFAULT NULL, 
    PRIMARY KEY (ChatID),
    FOREIGN KEY (shipID) REFERENCES Ship(shipID) 
    );

    CREATE TABLE `chat` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;

CREATE TABLE `chat_line` (
	`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
	`chat_id` INT(10) NOT NULL,
	`user_id` INT(10) NOT NULL,
	`line_text` TEXT NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK_chat` (`chat_id`) USING BTREE,
	INDEX `user_fk` (`user_id`) USING BTREE,
	CONSTRAINT `FK_chat` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `chat_user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;

CREATE TABLE `chat_user` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`handle` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;

CREATE TABLE 'task' (
	'taskID' INT(10) NOT NULL AUTO_INCREMENT,
	'taskName' VARCHAR(255) NOT NULL,
	'taskDesc' VARCHAR(65535) NOT NULL,
	'shipID'
	PRIMARY KEY (taskID),
    FOREIGN KEY (shipID) REFERENCES Ship(shipID) 
)
;