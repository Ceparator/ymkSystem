-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8 ;
USE `test` ;

-- -----------------------------------------------------
-- Table `test`.`faculty`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`faculty` ;

CREATE TABLE IF NOT EXISTS `test`.`faculty` (
  `facultyId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `desc` TEXT NOT NULL,
  PRIMARY KEY (`facultyId`),
  UNIQUE INDEX `if` (`facultyId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`cathedra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`cathedra` ;

CREATE TABLE IF NOT EXISTS `test`.`cathedra` (
  `cathedraId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `facultyId` BIGINT(20) UNSIGNED NOT NULL,
  `desc` TEXT NOT NULL,
  PRIMARY KEY (`cathedraId`),
  UNIQUE INDEX `ik` (`cathedraId` ASC),
  INDEX `fak` (`facultyId` ASC),
  CONSTRAINT `facultyId`
    FOREIGN KEY (`facultyId`)
    REFERENCES `test`.`faculty` (`facultyId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`competence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`competence` ;

CREATE TABLE IF NOT EXISTS `test`.`competence` (
  `competenceId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(100) NOT NULL COMMENT 'kod kompetencii',
  `desc` TEXT NOT NULL COMMENT 'znachenie kompetencii',
  PRIMARY KEY (`competenceId`),
  UNIQUE INDEX `ikomp` (`competenceId` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`discipline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`discipline` ;

CREATE TABLE IF NOT EXISTS `test`.`discipline` (
  `disciplineId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `cathedraId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'nomer kafedri',
  `desc` TEXT NOT NULL,
  PRIMARY KEY (`disciplineId`),
  UNIQUE INDEX `id` (`disciplineId` ASC),
  INDEX `cathedraId_idx` (`cathedraId` ASC),
  CONSTRAINT `cathedraId`
    FOREIGN KEY (`cathedraId`)
    REFERENCES `test`.`cathedra` (`cathedraId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`disc_comp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`disc_comp` ;

CREATE TABLE IF NOT EXISTS `test`.`disc_comp` (
  `disc_compId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `disciplineId` BIGINT(20) UNSIGNED NOT NULL,
  `competenceId` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`disc_compId`),
  UNIQUE INDEX `disc_compId_UNIQUE` (`disc_compId` ASC),
  INDEX `disciplineId_idx` (`disciplineId` ASC),
  INDEX `competenceId_idx` (`competenceId` ASC),
  CONSTRAINT `competenceId`
    FOREIGN KEY (`competenceId`)
    REFERENCES `test`.`competence` (`competenceId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `disciplineId`
    FOREIGN KEY (`disciplineId`)
    REFERENCES `test`.`discipline` (`disciplineId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`form`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`form` ;

CREATE TABLE IF NOT EXISTS `test`.`form` (
  `formId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL COMMENT 'nazvanie formi obuchenia',
  PRIMARY KEY (`formId`),
  UNIQUE INDEX `iform` (`formId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`level`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`level` ;

CREATE TABLE IF NOT EXISTS `test`.`level` (
  `levelId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`levelId`),
  UNIQUE INDEX `inp` (`levelId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`myuser`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`myuser` ;

CREATE TABLE IF NOT EXISTS `test`.`myuser` (
  `myuserId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'number of user',
  `email` VARCHAR(100) NOT NULL COMMENT 'registered email',
  `password` VARCHAR(32) NOT NULL COMMENT 'hash of password',
  `role` TINYINT(4) NOT NULL COMMENT '1-student, 2- teacher, 3 - admin',
  PRIMARY KEY (`myuserId`),
  UNIQUE INDEX `iu` (`myuserId` ASC),
  UNIQUE INDEX `email` (`email` ASC),
  INDEX `email_2` (`email` ASC),
  INDEX `pd` (`password` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`napravlenie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`napravlenie` ;

CREATE TABLE IF NOT EXISTS `test`.`napravlenie` (
  `napravlenieId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(300) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`napravlenieId`),
  UNIQUE INDEX `inp` (`napravlenieId` ASC),
  UNIQUE INDEX `inap` (`napravlenieId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`profile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`profile` ;

CREATE TABLE IF NOT EXISTS `test`.`profile` (
  `profileId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`profileId`),
  UNIQUE INDEX `ipr` (`profileId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`teacher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`teacher` ;

CREATE TABLE IF NOT EXISTS `test`.`teacher` (
  `teacherId` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(300) NOT NULL COMMENT 'prepodavatel',
  `cathedraId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'nomer kafedri',
  `desc` TEXT NOT NULL,
  `myuserId` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`teacherId`),
  UNIQUE INDEX `ip` (`teacherId` ASC),
  INDEX `cathedraId_idx` (`cathedraId` ASC),
  INDEX `userId_idx` (`myuserId` ASC),
  CONSTRAINT `cathedraIdd`
    FOREIGN KEY (`cathedraId`)
    REFERENCES `test`.`cathedra` (`cathedraId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `myuserId`
    FOREIGN KEY (`myuserId`)
    REFERENCES `test`.`myuser` (`myuserId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`umk`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`umk` ;

CREATE TABLE IF NOT EXISTS `test`.`umk` (
  `umkId` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `disciplineId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'index in discipline',
  `teacherId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'prepod index',
  `levelId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'uroven obrazovania index',
  `formId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'formi index',
  `facultyId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'fakultet index',
  `cathedraId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'kafedra index',
  `profileId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'profil index',
  `napravlenieId` BIGINT(20) UNSIGNED NOT NULL COMMENT 'napravlenie index',
  `title` TEXT NOT NULL COMMENT 'titul paper',
  `target` TEXT NOT NULL COMMENT 'celi part',
  `competence` TEXT NOT NULL COMMENT 'competencii part',
  `final` TEXT NOT NULL COMMENT 'final exams',
  `technology` TEXT NOT NULL COMMENT 'technology part',
  `grade` TEXT NOT NULL COMMENT 'ocenka part',
  `umprovision` TEXT NOT NULL COMMENT 'ucheb-metod obesp',
  `matprovision` TEXT NOT NULL COMMENT 'mat obesp part',
  `sogl1` TEXT NOT NULL COMMENT 'sogl part 1',
  `sogl2` TEXT NOT NULL COMMENT 'sogl part 2',
  `discname` TEXT NOT NULL COMMENT 'name of part disc',
  `semester` TEXT NOT NULL COMMENT 'semestr',
  `week` TEXT NOT NULL COMMENT 'nedela semestra',
  `lecture` TEXT NOT NULL COMMENT 'chasov likciy',
  `practice` TEXT NOT NULL COMMENT 'chasov praktik',
  `lab` TEXT NOT NULL COMMENT 'chasov laboratornih rabot',
  `controlwork` TEXT NOT NULL COMMENT 'chasov kontrolnih rabot',
  `kp` TEXT NOT NULL,
  `srs` TEXT NOT NULL COMMENT 'chasov samostoyatelnih abot',
  `interactive` TEXT NOT NULL COMMENT 'interactiv',
  `control` TEXT NOT NULL COMMENT 'tekushiy control',
  `dt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date of creating or updating',
  PRIMARY KEY (`umkId`),
  UNIQUE INDEX `ium` (`umkId` ASC),
  INDEX `disciplineId_idx` (`disciplineId` ASC, `teacherId` ASC),
  INDEX `teacherId_idx` (`teacherId` ASC),
  INDEX `levelId` (`levelId` ASC),
  INDEX `formId` (`formId` ASC),
  INDEX `facultyId` (`facultyId` ASC),
  INDEX `cathedraId` (`cathedraId` ASC),
  INDEX `profileId` (`profileId` ASC),
  INDEX `napravlenieId` (`napravlenieId` ASC),
  CONSTRAINT `umk_ibfk_1`
    FOREIGN KEY (`disciplineId`)
    REFERENCES `test`.`discipline` (`disciplineId`),
  CONSTRAINT `umk_ibfk_2`
    FOREIGN KEY (`teacherId`)
    REFERENCES `test`.`teacher` (`teacherId`),
  CONSTRAINT `umk_ibfk_3`
    FOREIGN KEY (`levelId`)
    REFERENCES `test`.`level` (`levelId`),
  CONSTRAINT `umk_ibfk_4`
    FOREIGN KEY (`formId`)
    REFERENCES `test`.`form` (`formId`),
  CONSTRAINT `umk_ibfk_5`
    FOREIGN KEY (`facultyId`)
    REFERENCES `test`.`faculty` (`facultyId`),
  CONSTRAINT `umk_ibfk_6`
    FOREIGN KEY (`cathedraId`)
    REFERENCES `test`.`cathedra` (`cathedraId`),
  CONSTRAINT `umk_ibfk_7`
    FOREIGN KEY (`profileId`)
    REFERENCES `test`.`profile` (`profileId`),
  CONSTRAINT `umk_ibfk_8`
    FOREIGN KEY (`napravlenieId`)
    REFERENCES `test`.`napravlenie` (`napravlenieId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `test`.`year`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test`.`year` ;

CREATE TABLE IF NOT EXISTS `test`.`year` (
  `yearId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`yearId`),
  UNIQUE INDEX `yearId_UNIQUE` (`yearId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
