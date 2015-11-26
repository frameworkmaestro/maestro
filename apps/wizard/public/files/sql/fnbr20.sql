SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `fnbr20_db` ;
CREATE SCHEMA IF NOT EXISTS `fnbr20_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `fnbr20_db` ;

-- -----------------------------------------------------
-- Table `fnbr20_db`.`Entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Entity` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Entity` (
  `idEntity` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `alias` VARCHAR(50) NOT NULL ,
  `type` CHAR(2) NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idOld` INT NULL ,
  PRIMARY KEY (`idEntity`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `alias_UNIQUE` ON `fnbr20_db`.`Entity` (`alias` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Language` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Language` (
  `idLanguage` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `language` VARCHAR(50) NULL COMMENT 'Two-letter ISO 639-1 language codes + region, See: http://www.w3.org/International/articles/language-tags/' ,
  `description` VARCHAR(50) NULL ,
  PRIMARY KEY (`idLanguage`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Translation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Translation` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Translation` (
  `idTranslation` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `resource` VARCHAR(255) NOT NULL ,
  `text` VARCHAR(1000) NULL ,
  `idLanguage` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idTranslation`) ,
  CONSTRAINT `fk_Translation_Language1`
    FOREIGN KEY (`idLanguage` )
    REFERENCES `fnbr20_db`.`Language` (`idLanguage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_NLS_Translation_Language1` ON `fnbr20_db`.`Translation` (`idLanguage` ASC) ;

CREATE INDEX `idx_resource` ON `fnbr20_db`.`Translation` (`resource` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Type` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Type` (
  `idType` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`idType`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`POS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`POS` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`POS` (
  `idPOS` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `POS` VARCHAR(50) NULL ,
  `entry` VARCHAR(255) NOT NULL ,
  `timeline` VARCHAR(255) NULL ,
  PRIMARY KEY (`idPOS`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Lemma`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Lemma` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Lemma` (
  `idLemma` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idPOS` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLemma`) ,
  CONSTRAINT `fk_Lemma_POS1`
    FOREIGN KEY (`idPOS` )
    REFERENCES `fnbr20_db`.`POS` (`idPOS` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Lemma_POS1` ON `fnbr20_db`.`Lemma` (`idPOS` ASC) ;

CREATE INDEX `idx_Lemma_name` ON `fnbr20_db`.`Lemma` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`LU`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`LU` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`LU` (
  `idLU` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `senseDescription` VARCHAR(1000) NULL ,
  `active` TINYINT(1) NULL ,
  `importNum` INT UNSIGNED NULL ,
  `incorporatedFE` INT UNSIGNED NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  `idLemma` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLU`) ,
  CONSTRAINT `fk_LU_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LU_Lemma1`
    FOREIGN KEY (`idLemma` )
    REFERENCES `fnbr20_db`.`Lemma` (`idLemma` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_LU_Entity1` ON `fnbr20_db`.`LU` (`idEntity` ASC) ;

CREATE INDEX `idx_LU_Lemma1` ON `fnbr20_db`.`LU` (`idLemma` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Frame`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Frame` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Frame` (
  `idFrame` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idFrame`) ,
  CONSTRAINT `fk_Schema_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Schema_Entity1` ON `fnbr20_db`.`Frame` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Color`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Color` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Color` (
  `idColor` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL ,
  `rgbFg` CHAR(6) NULL ,
  `rgbBg` CHAR(6) NULL ,
  PRIMARY KEY (`idColor`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`FrameElement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`FrameElement` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`FrameElement` (
  `idFrameElement` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NULL ,
  `active` TINYINT(1) NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  `idColor` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idFrameElement`) ,
  CONSTRAINT `fk_Element_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Element_Color1`
    FOREIGN KEY (`idColor` )
    REFERENCES `fnbr20_db`.`Color` (`idColor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Element_Entity1` ON `fnbr20_db`.`FrameElement` (`idEntity` ASC) ;

CREATE INDEX `idx_Element_Color1` ON `fnbr20_db`.`FrameElement` (`idColor` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Template` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Template` (
  `idTemplate` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idTemplate`) ,
  CONSTRAINT `fk_Template_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Template_Entity1` ON `fnbr20_db`.`Template` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Domain`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Domain` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Domain` (
  `idDomain` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`idDomain`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`RelationType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`RelationType` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`RelationType` (
  `idRelationType` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `nameEntity1` VARCHAR(255) NOT NULL ,
  `nameEntity2` VARCHAR(255) NOT NULL ,
  `idDomain` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idRelationType`) ,
  CONSTRAINT `fk_RelationType_Domain1`
    FOREIGN KEY (`idDomain` )
    REFERENCES `fnbr20_db`.`Domain` (`idDomain` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_RelationType_Domain1` ON `fnbr20_db`.`RelationType` (`idDomain` ASC) ;

CREATE INDEX `idx_resource_nameEntity1` ON `fnbr20_db`.`RelationType` (`nameEntity1` ASC) ;

CREATE INDEX `idx_resource_nameEntity2` ON `fnbr20_db`.`RelationType` (`nameEntity2` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`EntityRelation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`EntityRelation` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`EntityRelation` (
  `idEntityRelation` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idRelationType` INT(11) UNSIGNED NOT NULL ,
  `idEntity1` INT(11) UNSIGNED NOT NULL ,
  `idEntity2` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idEntityRelation`) ,
  CONSTRAINT `fk_EntityRelation_RelationType1`
    FOREIGN KEY (`idRelationType` )
    REFERENCES `fnbr20_db`.`RelationType` (`idRelationType` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EntityRelation_Entity1`
    FOREIGN KEY (`idEntity1` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EntityRelation_Entity2`
    FOREIGN KEY (`idEntity2` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_EntityRelation_RelationType1` ON `fnbr20_db`.`EntityRelation` (`idRelationType` ASC) ;

CREATE INDEX `idx_EntityRelation_Entity1` ON `fnbr20_db`.`EntityRelation` (`idEntity1` ASC) ;

CREATE INDEX `idx_EntityRelation_Entity2` ON `fnbr20_db`.`EntityRelation` (`idEntity2` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Lexeme`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Lexeme` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Lexeme` (
  `idLexeme` INT(11) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idPOS` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLexeme`) ,
  CONSTRAINT `fk_Lexeme_POS1`
    FOREIGN KEY (`idPOS` )
    REFERENCES `fnbr20_db`.`POS` (`idPOS` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Lexeme_POS1` ON `fnbr20_db`.`Lexeme` (`idPOS` ASC) ;

CREATE INDEX `idx_Lexeme_name` ON `fnbr20_db`.`Lexeme` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`WordForm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`WordForm` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`WordForm` (
  `idWordForm` INT(11) UNSIGNED NOT NULL ,
  `form` VARCHAR(255) NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idLexeme` INT(11) UNSIGNED NOT NULL ,
  `idLanguage` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idWordForm`) ,
  CONSTRAINT `fk_WordForm_Lexeme1`
    FOREIGN KEY (`idLexeme` )
    REFERENCES `fnbr20_db`.`Lexeme` (`idLexeme` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WordForm_Language1`
    FOREIGN KEY (`idLanguage` )
    REFERENCES `fnbr20_db`.`Language` (`idLanguage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_WordForm_Lexeme1` ON `fnbr20_db`.`WordForm` (`idLexeme` ASC) ;

CREATE INDEX `idx_WordForm_Language1` ON `fnbr20_db`.`WordForm` (`idLanguage` ASC) ;

CREATE INDEX `idx_WordForm_form` ON `fnbr20_db`.`WordForm` (`form` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`LexemeEntry`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`LexemeEntry` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`LexemeEntry` (
  `idLexemeEntry` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `lexemeOrder` INT NULL ,
  `breakBefore` TINYINT(1) NULL ,
  `headWord` TINYINT(1) NULL ,
  `idLexeme` INT(11) UNSIGNED NOT NULL ,
  `idLemma` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLexemeEntry`) ,
  CONSTRAINT `fk_LexemeEntry_Lexeme1`
    FOREIGN KEY (`idLexeme` )
    REFERENCES `fnbr20_db`.`Lexeme` (`idLexeme` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LexemeEntry_Lemma1`
    FOREIGN KEY (`idLemma` )
    REFERENCES `fnbr20_db`.`Lemma` (`idLemma` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_LexemeEntry_Lexeme1` ON `fnbr20_db`.`LexemeEntry` (`idLexeme` ASC) ;

CREATE INDEX `idx_LexemeEntry_Lemma1` ON `fnbr20_db`.`LexemeEntry` (`idLemma` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Genre`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Genre` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Genre` (
  `idGenre` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NULL ,
  PRIMARY KEY (`idGenre`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Corpus`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Corpus` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Corpus` (
  `idCorpus` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NULL ,
  `timeline` VARCHAR(255) NULL ,
  PRIMARY KEY (`idCorpus`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Document` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Document` (
  `idDocument` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `author` VARCHAR(255) NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idGenre` INT(11) UNSIGNED NOT NULL ,
  `idCorpus` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idDocument`) ,
  CONSTRAINT `fk_Document_Genre1`
    FOREIGN KEY (`idGenre` )
    REFERENCES `fnbr20_db`.`Genre` (`idGenre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Document_Corpus1`
    FOREIGN KEY (`idCorpus` )
    REFERENCES `fnbr20_db`.`Corpus` (`idCorpus` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Document_Genre1` ON `fnbr20_db`.`Document` (`idGenre` ASC) ;

CREATE INDEX `idx_Document_Corpus1` ON `fnbr20_db`.`Document` (`idCorpus` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Paragraph`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Paragraph` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Paragraph` (
  `idParagraph` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `documentOrder` INT NULL ,
  `idDocument` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idParagraph`) ,
  CONSTRAINT `fk_Paragraph_Document1`
    FOREIGN KEY (`idDocument` )
    REFERENCES `fnbr20_db`.`Document` (`idDocument` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Paragraph_Document1` ON `fnbr20_db`.`Paragraph` (`idDocument` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Sentence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Sentence` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Sentence` (
  `idSentence` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `text` VARCHAR(1000) NULL ,
  `paragraphOrder` INT NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idParagraph` INT(11) UNSIGNED NOT NULL ,
  `idLanguage` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idSentence`) ,
  CONSTRAINT `fk_Sentence_Paragraph1`
    FOREIGN KEY (`idParagraph` )
    REFERENCES `fnbr20_db`.`Paragraph` (`idParagraph` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sentence_Language1`
    FOREIGN KEY (`idLanguage` )
    REFERENCES `fnbr20_db`.`Language` (`idLanguage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Sentence_Paragraph1` ON `fnbr20_db`.`Sentence` (`idParagraph` ASC) ;

CREATE INDEX `idx_Sentence_Language1` ON `fnbr20_db`.`Sentence` (`idLanguage` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`GenericLabel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`GenericLabel` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`GenericLabel` (
  `idGenericLabel` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  `idColor` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idGenericLabel`) ,
  CONSTRAINT `fk_GenericLabel_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_GenericLabel_Color1`
    FOREIGN KEY (`idColor` )
    REFERENCES `fnbr20_db`.`Color` (`idColor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_GenericLabel_Entity1` ON `fnbr20_db`.`GenericLabel` (`idEntity` ASC) ;

CREATE INDEX `idx_GenericLabel_Color1` ON `fnbr20_db`.`GenericLabel` (`idColor` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`LayerGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`LayerGroup` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`LayerGroup` (
  `idLayerGroup` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL ,
  PRIMARY KEY (`idLayerGroup`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`LayerType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`LayerType` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`LayerType` (
  `idLayerType` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `allowsApositional` TINYINT(1) NULL ,
  `isAnnotation` TINYINT(1) NULL ,
  `idLayerGroup` INT(11) UNSIGNED NOT NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  `idLanguage` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLayerType`) ,
  CONSTRAINT `fk_LayerType_LayerGroup1`
    FOREIGN KEY (`idLayerGroup` )
    REFERENCES `fnbr20_db`.`LayerGroup` (`idLayerGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LayerType_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LayerType_Language1`
    FOREIGN KEY (`idLanguage` )
    REFERENCES `fnbr20_db`.`Language` (`idLanguage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_LayerType_LayerGroup1` ON `fnbr20_db`.`LayerType` (`idLayerGroup` ASC) ;

CREATE INDEX `idx_LayerType_Entity1` ON `fnbr20_db`.`LayerType` (`idEntity` ASC) ;

CREATE INDEX `idx_LayerType_Language1` ON `fnbr20_db`.`LayerType` (`idLanguage` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`SubCorpus`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`SubCorpus` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`SubCorpus` (
  `idSubCorpus` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `rank` INT NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idSubCorpus`) ,
  CONSTRAINT `fk_SubCorpus_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_SubCorpus_Entity1` ON `fnbr20_db`.`SubCorpus` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`TypeInstance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`TypeInstance` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`TypeInstance` (
  `idTypeInstance` INT NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `info` VARCHAR(50) NULL ,
  `flag` TINYINT(1) NULL ,
  `idType` INT(11) UNSIGNED NOT NULL ,
  `idColor` INT(11) UNSIGNED NOT NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idTypeInstance`) ,
  CONSTRAINT `fk_TypeInstance_Type1`
    FOREIGN KEY (`idType` )
    REFERENCES `fnbr20_db`.`Type` (`idType` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TypeInstance_Color1`
    FOREIGN KEY (`idColor` )
    REFERENCES `fnbr20_db`.`Color` (`idColor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TypeInstance_Entity1`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_TypeInstance_Type` ON `fnbr20_db`.`TypeInstance` (`idType` ASC) ;

CREATE INDEX `idx_TypeInstance_Color` ON `fnbr20_db`.`TypeInstance` (`idColor` ASC) ;

CREATE INDEX `idx_TypeInstance_Entity` ON `fnbr20_db`.`TypeInstance` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`AnnotationSet`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`AnnotationSet` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`AnnotationSet` (
  `idAnnotationSet` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `timeline` VARCHAR(255) NULL ,
  `idSubCorpus` INT(11) UNSIGNED NOT NULL ,
  `idSentence` INT(11) UNSIGNED NOT NULL ,
  `idAnnotationStatus` INT NOT NULL ,
  PRIMARY KEY (`idAnnotationSet`) ,
  CONSTRAINT `fk_AnnoatationSet_SubCorpus1`
    FOREIGN KEY (`idSubCorpus` )
    REFERENCES `fnbr20_db`.`SubCorpus` (`idSubCorpus` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AnnotationSet_Sentence1`
    FOREIGN KEY (`idSentence` )
    REFERENCES `fnbr20_db`.`Sentence` (`idSentence` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AnnotationSet_TypeInstance1`
    FOREIGN KEY (`idAnnotationStatus` )
    REFERENCES `fnbr20_db`.`TypeInstance` (`idTypeInstance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_AnnoatationSet_SubCorpus1` ON `fnbr20_db`.`AnnotationSet` (`idSubCorpus` ASC) ;

CREATE INDEX `idx_AnnotationSet_Sentence1` ON `fnbr20_db`.`AnnotationSet` (`idSentence` ASC) ;

CREATE INDEX `idx_AnnotationSet_TypeInstance1` ON `fnbr20_db`.`AnnotationSet` (`idAnnotationStatus` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Layer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Layer` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Layer` (
  `idLayer` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `rank` INT NULL ,
  `timeline` VARCHAR(255) NULL ,
  `idAnnotationSet` INT(11) UNSIGNED NOT NULL ,
  `idLayerType` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idLayer`) ,
  CONSTRAINT `fk_Layer_AnnotationSet1`
    FOREIGN KEY (`idAnnotationSet` )
    REFERENCES `fnbr20_db`.`AnnotationSet` (`idAnnotationSet` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Layer_LayerType1`
    FOREIGN KEY (`idLayerType` )
    REFERENCES `fnbr20_db`.`LayerType` (`idLayerType` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Layer_AnnotationSet1` ON `fnbr20_db`.`Layer` (`idAnnotationSet` ASC) ;

CREATE INDEX `idx_Layer_LayerType1` ON `fnbr20_db`.`Layer` (`idLayerType` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Label`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Label` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Label` (
  `idLabel` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `startChar` INT NULL ,
  `endChar` INT NULL ,
  `multi` TINYINT(1) NULL ,
  `idLabelType` INT(11) UNSIGNED NOT NULL COMMENT 'Reference to FrameElement or GenericLabel' ,
  `idLayer` INT(11) UNSIGNED NOT NULL ,
  `idInstantiationType` INT NOT NULL ,
  PRIMARY KEY (`idLabel`) ,
  CONSTRAINT `fk_Label_LabelType`
    FOREIGN KEY (`idLabelType` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Label_Layer`
    FOREIGN KEY (`idLayer` )
    REFERENCES `fnbr20_db`.`Layer` (`idLayer` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Label_TypeInstance`
    FOREIGN KEY (`idInstantiationType` )
    REFERENCES `fnbr20_db`.`TypeInstance` (`idTypeInstance` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Label_LabelType` ON `fnbr20_db`.`Label` (`idLabelType` ASC) ;

CREATE INDEX `idx_Label_Layer` ON `fnbr20_db`.`Label` (`idLayer` ASC) ;

CREATE INDEX `idx_Label_TypeInstance1` ON `fnbr20_db`.`Label` (`idInstantiationType` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_person` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_person` (
  `idPerson` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `email` VARCHAR(255) NULL ,
  `nick` VARCHAR(50) NULL ,
  PRIMARY KEY (`idPerson`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_user` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_user` (
  `idUser` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(50) NULL ,
  `pwd` VARCHAR(255) NULL ,
  `passMD5` VARCHAR(255) NULL ,
  `theme` VARCHAR(50) NULL ,
  `active` TINYINT(1) NULL ,
  `idPerson` INT NOT NULL ,
  PRIMARY KEY (`idUser`) ,
  CONSTRAINT `fk_person`
    FOREIGN KEY (`idPerson` )
    REFERENCES `fnbr20_db`.`auth_person` (`idPerson` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_auth_user_auth_person1` ON `fnbr20_db`.`auth_user` (`idPerson` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_group` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_group` (
  `idGroup` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL ,
  `description` VARCHAR(255) NULL ,
  PRIMARY KEY (`idGroup`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_transaction` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_transaction` (
  `idTransaction` INT NOT NULL ,
  `name` VARCHAR(50) NULL ,
  `description` VARCHAR(255) NULL ,
  PRIMARY KEY (`idTransaction`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_access`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_access` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_access` (
  `idAccess` INT NOT NULL AUTO_INCREMENT ,
  `rights` INT NULL ,
  `idGroup` INT NOT NULL ,
  `idTransaction` INT NOT NULL ,
  PRIMARY KEY (`idAccess`) ,
  CONSTRAINT `fk_group`
    FOREIGN KEY (`idGroup` )
    REFERENCES `fnbr20_db`.`auth_group` (`idGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaction`
    FOREIGN KEY (`idTransaction` )
    REFERENCES `fnbr20_db`.`auth_transaction` (`idTransaction` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_auth_access_auth_group1` ON `fnbr20_db`.`auth_access` (`idGroup` ASC) ;

CREATE INDEX `idx_auth_access_auth_transaction1` ON `fnbr20_db`.`auth_access` (`idTransaction` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_user_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_user_group` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_user_group` (
  `idUser` INT NOT NULL ,
  `idGroup` INT NOT NULL ,
  PRIMARY KEY (`idUser`, `idGroup`) ,
  CONSTRAINT `fk_auth_user`
    FOREIGN KEY (`idUser` )
    REFERENCES `fnbr20_db`.`auth_user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_auth_group`
    FOREIGN KEY (`idGroup` )
    REFERENCES `fnbr20_db`.`auth_group` (`idGroup` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_auth_user_has_auth_group_auth_group1` ON `fnbr20_db`.`auth_user_group` (`idGroup` ASC) ;

CREATE INDEX `idx_auth_user_has_auth_group_auth_user1` ON `fnbr20_db`.`auth_user_group` (`idUser` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`auth_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`auth_log` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`auth_log` (
  `idLog` INT NOT NULL AUTO_INCREMENT ,
  `ts` TIMESTAMP NULL ,
  `operation` VARCHAR(50) NULL ,
  `idUser` INT NOT NULL ,
  PRIMARY KEY (`idLog`) ,
  CONSTRAINT `fk_user`
    FOREIGN KEY (`idUser` )
    REFERENCES `fnbr20_db`.`auth_user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_auth_log_auth_user1` ON `fnbr20_db`.`auth_log` (`idUser` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Entry`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Entry` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Entry` (
  `idEntry` INT NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NULL ,
  `name` VARCHAR(255) NULL ,
  `description` VARCHAR(1000) NULL ,
  `nick` VARCHAR(50) NULL ,
  `idLanguage` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idEntry`) ,
  CONSTRAINT `fk_Entry_Language`
    FOREIGN KEY (`idLanguage` )
    REFERENCES `fnbr20_db`.`Language` (`idLanguage` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Entry_Language` ON `fnbr20_db`.`Entry` (`idLanguage` ASC) ;

CREATE INDEX `idx_Entry_Entry` ON `fnbr20_db`.`Entry` (`entry` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Construction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Construction` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Construction` (
  `idConstruction` INT NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idConstruction`) ,
  CONSTRAINT `fk_Construction_Entity`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_Construction_Entity` ON `fnbr20_db`.`Construction` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`ConstructionElement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`ConstructionElement` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`ConstructionElement` (
  `idConstructionElement` INT NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NULL ,
  `active` TINYINT(1) NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  `idColor` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idConstructionElement`) ,
  CONSTRAINT `fk_ConstructionElement_Entity`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ConstructionElement_Color`
    FOREIGN KEY (`idColor` )
    REFERENCES `fnbr20_db`.`Color` (`idColor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_ConstructionElement_Entity` ON `fnbr20_db`.`ConstructionElement` (`idEntity` ASC) ;

CREATE INDEX `idx_ConstructionElement_Color` ON `fnbr20_db`.`ConstructionElement` (`idColor` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`SemanticType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`SemanticType` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`SemanticType` (
  `idSemanticType` INT NOT NULL AUTO_INCREMENT ,
  `entry` VARCHAR(255) NOT NULL ,
  `idEntity` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idSemanticType`) ,
  CONSTRAINT `fk_SemanticType_Entity`
    FOREIGN KEY (`idEntity` )
    REFERENCES `fnbr20_db`.`Entity` (`idEntity` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `idx_SemanticType_Entity1` ON `fnbr20_db`.`SemanticType` (`idEntity` ASC) ;


-- -----------------------------------------------------
-- Table `fnbr20_db`.`Timeline`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fnbr20_db`.`Timeline` ;

CREATE  TABLE IF NOT EXISTS `fnbr20_db`.`Timeline` (
  `idTimeline` INT NOT NULL AUTO_INCREMENT ,
  `timeline` VARCHAR(255) NULL ,
  `order` INT UNSIGNED NULL ,
  `dateTime` DATETIME NULL ,
  `author` VARCHAR(50) NULL ,
  PRIMARY KEY (`idTimeline`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
