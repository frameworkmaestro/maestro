SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `tvab` DEFAULT CHARACTER SET latin1 ;
USE `tvab` ;

-- -----------------------------------------------------
-- Table `tvab`.`voluntario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`voluntario` (
  `IDVoluntario` INT(11) NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Endereco` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Numero` VARCHAR(20) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Bairro` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Cidade` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `CEP` VARCHAR(10) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Estado` VARCHAR(2) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Email` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `TelefoneResidencial` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `TelefoneComercial` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `TelefoneCelular` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Profissao` VARCHAR(100) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataNascimento` VARCHAR(10) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Identidade` VARCHAR(25) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `OrgaoExpedidor` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataExpedicao` VARCHAR(10) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `CPF` VARCHAR(25) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `GrauEscolaridade` VARCHAR(50) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `FichaAtiva` TINYINT(1) NULL DEFAULT '0' ,
  `FichaAtiva20110928` TINYINT(1) NULL DEFAULT '0' ,
  `TermoVoluntario` TINYINT(4) NULL DEFAULT '0' ,
  `Escolaridade` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataCadastro` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `EmailExtra` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Orkut` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Facebook` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Twitter` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataAtualizacao` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataAtualizacaoSecretaria` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `DataAtualizacaoVoluntario` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`IDVoluntario`) ,
  INDEX `Identidade` (`Identidade` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 1495
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_general_ci;


-- -----------------------------------------------------
-- Table `tvab`.`departamento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`departamento` (
  `IDDepartamento` INT NOT NULL ,
  `Nome` VARCHAR(45) NULL ,
  PRIMARY KEY (`IDDepartamento`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tvab`.`setor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`setor` (
  `IDSetor` INT NOT NULL ,
  `Nome` VARCHAR(45) NULL ,
  `IDDepartamento` INT NOT NULL ,
  PRIMARY KEY (`IDSetor`) ,
  INDEX `fk_setor_departamento1` (`IDDepartamento` ASC) ,
  CONSTRAINT `fk_setor_departamento1`
    FOREIGN KEY (`IDDepartamento` )
    REFERENCES `tvab`.`departamento` (`IDDepartamento` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tvab`.`atividade`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`atividade` (
  `IDAtividade` INT(11) NOT NULL ,
  `Nome` VARCHAR(255) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NOT NULL ,
  `Status` VARCHAR(1) CHARACTER SET 'latin1' COLLATE 'latin1_general_ci' NULL DEFAULT NULL ,
  `Dia` TINYINT(4) NULL DEFAULT NULL ,
  `HoraInicioTarefa` TIME NULL DEFAULT NULL ,
  `HoraFimTarefa` TIME NULL DEFAULT NULL ,
  `MinimoPresenca` INT NULL DEFAULT NULL ,
  `IDSetor` INT NOT NULL ,
  PRIMARY KEY (`IDAtividade`) ,
  INDEX `fk_atividade_setor1` (`IDSetor` ASC) ,
  CONSTRAINT `fk_atividade_setor1`
    FOREIGN KEY (`IDSetor` )
    REFERENCES `tvab`.`setor` (`IDSetor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `tvab`.`registrocoletor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`registrocoletor` (
  `IDRegistroColetor` INT NOT NULL ,
  `DataHora` TIMESTAMP NOT NULL ,
  `Tipo` VARCHAR(1) NULL ,
  `IDVoluntario` INT(11) NOT NULL ,
  `IDAtividade` INT(11) NOT NULL ,
  PRIMARY KEY (`IDRegistroColetor`) ,
  INDEX `fk_central_registro_voluntario` (`IDVoluntario` ASC) ,
  INDEX `fk_central_registro_central_tarefa1` (`IDAtividade` ASC) ,
  CONSTRAINT `fk_central_registro_voluntario`
    FOREIGN KEY (`IDVoluntario` )
    REFERENCES `tvab`.`voluntario` (`IDVoluntario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_central_registro_central_tarefa1`
    FOREIGN KEY (`IDAtividade` )
    REFERENCES `tvab`.`atividade` (`IDAtividade` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tvab`.`atividaderesponsavel`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`atividaderesponsavel` (
  `IDAtividadeResponsavel` INT NOT NULL ,
  `DataInicio` DATE NULL ,
  `DataFim` DATE NULL ,
  `IDAtividade` INT(11) NOT NULL ,
  `IDVoluntario` INT(11) NOT NULL ,
  PRIMARY KEY (`IDAtividadeResponsavel`) ,
  INDEX `fk_atividaderesponsavel_atividade1` (`IDAtividade` ASC) ,
  INDEX `fk_atividaderesponsavel_voluntario1` (`IDVoluntario` ASC) ,
  CONSTRAINT `fk_atividaderesponsavel_atividade1`
    FOREIGN KEY (`IDAtividade` )
    REFERENCES `tvab`.`atividade` (`IDAtividade` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atividaderesponsavel_voluntario1`
    FOREIGN KEY (`IDVoluntario` )
    REFERENCES `tvab`.`voluntario` (`IDVoluntario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tvab`.`atividadevoluntario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`atividadevoluntario` (
  `IDAtividadeVoluntario` INT NOT NULL ,
  `DataInicio` DATE NULL ,
  `DataFim` DATE NULL ,
  `IDAtividade` INT(11) NOT NULL ,
  `IDVoluntario` INT(11) NOT NULL ,
  PRIMARY KEY (`IDAtividadeVoluntario`) ,
  INDEX `fk_atividadevoluntario_atividade1` (`IDAtividade` ASC) ,
  INDEX `fk_atividadevoluntario_voluntario1` (`IDVoluntario` ASC) ,
  CONSTRAINT `fk_atividadevoluntario_atividade1`
    FOREIGN KEY (`IDAtividade` )
    REFERENCES `tvab`.`atividade` (`IDAtividade` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atividadevoluntario_voluntario1`
    FOREIGN KEY (`IDVoluntario` )
    REFERENCES `tvab`.`voluntario` (`IDVoluntario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tvab`.`presencavoluntario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tvab`.`presencavoluntario` (
  `IDPresencaVoluntario` INT NOT NULL ,
  `Data` DATE NULL ,
  `IDAtividadeVoluntario` INT NOT NULL ,
  PRIMARY KEY (`IDPresencaVoluntario`) ,
  INDEX `fk_presencavoluntario_atividadevoluntario1` (`IDAtividadeVoluntario` ASC) ,
  CONSTRAINT `fk_presencavoluntario_atividadevoluntario1`
    FOREIGN KEY (`IDAtividadeVoluntario` )
    REFERENCES `tvab`.`atividadevoluntario` (`IDAtividadeVoluntario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
