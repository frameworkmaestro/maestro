--
-- Implantação das constraints
-- 

--
-- Relation Group
--

delete from entry where entry like 'rgp_constraints';

insert into entry(entry, name, description, nick, idLanguage)
SELECT 'rgp_constraints', 'Relações de Restrições', 'Relações de Restrições', '', 1;

insert into entry(entry, name, description, nick, idLanguage)
SELECT 'rgp_constraints', 'Constraints Relations', 'Constraints Relations', '', 2;

insert into entry(entry, name, description, nick, idLanguage)
SELECT 'rgp_constraints', 'Constraints Relations', 'Constraints Relations', '', 3;

insert into entry(entry, name, description, nick, idLanguage)
SELECT 'rgp_constraints', 'Constraints Relations', 'Constraints Relations', '', 4;

insert into RelationGroup (entry) values ('rgp_constraints');

--
--  Relation Type
--

-- relation type "rel_constraint_frame"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_frame','rel_constraint_frame','Constraint to a specific Frame','rel_constraint_frame',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_frame','rel_constraint_frame','Constraint to a specific Frame','rel_constraint_frame',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_frame','rel_constraint_frame','Constraint to a specific Frame','rel_constraint_frame',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_frame','rel_constraint_frame','Constraint to a specific Frame','rel_constraint_frame',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity2','constrained_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity2','constrained_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity2','constrained_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity2','constrained_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity3','constrained_by_frame',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity3','constrained_by_frame',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity3','constrained_by_frame',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_frame_entity3','constrained_by_frame',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_frame','rel_constraint_frame_entity1','rel_constraint_frame_entity2','rel_constraint_frame_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);

-- relation type "rel_constraint_semtype"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_semtype','rel_constraint_semtype','Constraint to a Semantic Type','rel_constraint_semtype',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_semtype','rel_constraint_semtype','Constraint to a Semantic Type','rel_constraint_semtype',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_semtype','rel_constraint_semtype','Constraint to a Semantic Type','rel_constraint_semtype',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_semtype','rel_constraint_semtype','Constraint to a Semantic Type','rel_constraint_semtype',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity2','constrained_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity2','constrained_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity2','constrained_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity2','constrained_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity3','constrained_by_semantic_type',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity3','constrained_by_semantic_type',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity3','constrained_by_semantic_type',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_semtype_entity3','constrained_by_semantic_type',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_semtype','rel_constraint_semtype_entity1','rel_constraint_semtype_entity2','rel_constraint_semtype_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);

-- relation type "rel_constraint_cxn"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_cxn','rel_constraint_cxn','Constraint to a specific Cxn','rel_constraint_cxn',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_cxn','rel_constraint_cxn','Constraint to a specific Cxn','rel_constraint_cxn',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_cxn','rel_constraint_cxn','Constraint to a specific Cxn','rel_constraint_cxn',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_cxn','rel_constraint_cxn','Constraint to a specific Cxn','rel_constraint_cxn',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity2','constrained_ce',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity2','constrained_ce',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity2','constrained_ce',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity2','constrained_ce',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity3','constrained_by_construction',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity3','constrained_by_construction',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity3','constrained_by_construction',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_cxn_entity3','constrained_by_construction',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_cxn','rel_constraint_cxn_entity1','rel_constraint_cxn_entity2','rel_constraint_cxn_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);

-- relation type "rel_constraint_framefamily"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_framefamily','rel_constraint_framefamily','Constraint to a Frame family','rel_constraint_framefamily',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_framefamily','rel_constraint_framefamily','Constraint to a Frame family','rel_constraint_framefamily',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_framefamily','rel_constraint_framefamily','Constraint to a Frame family','rel_constraint_framefamily',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_framefamily','rel_constraint_framefamily','Constraint to a Frame family','rel_constraint_framefamily',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity2','constrained_ce',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity2','constrained_ce',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity2','constrained_ce',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity2','constrained_ce',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity3','constrained_by_frame_family',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity3','constrained_by_frame_family',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity3','constrained_by_frame_family',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_framefamily_entity3','constrained_by_frame_family',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_framefamily','rel_constraint_framefamily_entity1','rel_constraint_framefamily_entity2','rel_constraint_framefamily_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);

-- relation type "rel_constraint_before"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_before','rel_constraint_before','Constraint CE order','rel_constraint_before',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_before','rel_constraint_before','Constraint CE order','rel_constraint_before',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_before','rel_constraint_before','Constraint CE order','rel_constraint_before',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_before','rel_constraint_before','Constraint CE order','rel_constraint_before',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity2','constrained_ce',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity2','constrained_ce',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity2','constrained_ce',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity2','constrained_ce',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity3','constrained_by_ce',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity3','constrained_by_ce',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity3','constrained_by_ce',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_before_entity3','constrained_by_ce',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_before','rel_constraint_before_entity1','rel_constraint_before_entity2','rel_constraint_before_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);

-- relation type "rel_constraint_element"

INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_element','rel_constraint_element','Constraint CE order','rel_constraint_element',1);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_element','rel_constraint_element','Constraint CE order','rel_constraint_element',2);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_element','rel_constraint_element','Constraint CE order','rel_constraint_element',3);
INSERT INTO Entry ( entry,name,description,nick,idLanguage ) 
    VALUES ('rel_constraint_element','rel_constraint_element','Constraint CE order','rel_constraint_element',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constraint_entity',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constraint_entity',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constraint_entity',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constraint_entity',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constrained_constraint',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constrained_constraint',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constrained_constraint',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity1','constrained_constraint',4);

INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity2','constrained_by_element',1);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity2','constrained_by_element',2);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity2','constrained_by_element',3);
INSERT INTO Translation ( resource,text,idLanguage ) 
    VALUES ('rel_constraint_element_entity2','constrained_by_element',4);

INSERT INTO RelationType ( entry,nameEntity1,nameEntity2,nameEntity3,idRelationGroup,idDomain ) 
    VALUES ('rel_constraint_element','rel_constraint_element_entity1','rel_constraint_element_entity2','rel_constraint_element_entity3',
    (select idRelationGroup from RelationGroup where entry='rgp_constraints'),1);





