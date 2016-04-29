--
-- EntryLanguage
-- 
create or replace view view_entrylanguage as
select entry.idEntry, entry.entry, entry.name, entry.description, entry.nick, entry.idLanguage, language.language
from Entry inner join Language on (entry.idLanguage = language.idLanguage);

--
-- Frame
--
create or replace view view_frame as
select frame.idFrame, frame.entry, frame.active, frame.idEntity
from Frame;

--
-- FrameElement
--
create or replace view view_frameelement as
select frame.idFrame, frame.entry as frameEntry, frame.idEntity as frameIdEntity, fe.idFrameElement, fe.entry, fe.active, fe.idEntity, fe.idColor, ti.entry as typeEntry
from frameelement fe join entityrelation er1 on (fe.idEntity = er1.idEntity1)
join relationtype rt1 on (er1.idRelationType = rt1.idRelationType)
join frame on (er1.idEntity2 = frame.idEntity)
join entityrelation er2 on (fe.idEntity = er2.idEntity1)
join relationtype rt2 on (er2.idRelationType = rt2.idRelationType)
join typeinstance ti on (er2.idEntity2 = ti.idEntity)
where (rt1.entry = 'rel_elementof') and (rt2.entry = 'rel_hastype');

--
-- CoreType
-- 
create or replace view view_coretype as
select ti.entry, ti.info, ti.flag, ti.idType, ti.idColor, ti.idEntity
from typeinstance ti join type on (ti.idType = type.idType)
where (type.entry = 'typ_coretype');

--
-- AnnotationStatusType
-- 
create or replace view view_annotationstatustype as
select ti.entry, ti.info, ti.flag, ti.idType, ti.idColor, ti.idEntity
from typeinstance ti join type on (ti.idType = type.idType)
where (type.entry = 'typ_annotationstatustype');

--
-- InstantiationType
-- 
create or replace view view_instantiationtype as
select ti.entry, ti.info, ti.flag, ti.idType, ti.idColor, ti.idEntity
from typeinstance ti join type on (ti.idType = type.idType)
where (type.entry = 'typ_instantiationtype');


--
-- LU
--
create or replace view view_lu as
select lu.idLU, lu.name, lu.senseDescription, lu.active, lu. importNum, lu.incorporatedFE, lu.idEntity, lu.idLemma, frame.idFrame, frame.entry as frameEntry, lemma.name as lemmaName, lemma.idPOS, lemma.idLanguage
from lu join entityrelation er1 on (lu.idEntity = er1.idEntity1)
join relationtype rt1 on (er1.idRelationType = rt1.idRelationType)
join frame on (er1.idEntity2 = frame.idEntity)
join lemma on (lu.idLemma = lemma.idLemma)
where (rt1.entry = 'rel_evokes');

--
-- SubCorpusLU
--
create or replace view view_subcorpuslu as
select sc.idSubCorpus, sc.name, sc.rank, lu.idLU
from lu join entityrelation er1 on (lu.idEntity = er1.idEntity1)
join relationtype rt1 on (er1.idRelationType = rt1.idRelationType)
join subcorpus sc on (er1.idEntity2 = sc.idEntity)
where (rt1.entry = 'rel_hassubcorpus');

--
-- SubCorpusCxn
--
create or replace view view_subcorpuscxn as
select sc.idSubCorpus, sc.name, sc.rank, cxn.idConstruction
from construction cxn join entityrelation er1 on (cxn.idEntity = er1.idEntity1)
join relationtype rt1 on (er1.idRelationType = rt1.idRelationType)
join subcorpus sc on (er1.idEntity2 = sc.idEntity)
where (rt1.entry = 'rel_hassubcorpus');

--
-- AnnotationSet
--
create or replace view view_annotationset as
select a.idAnnotationSet, a.idSubCorpus, a.idSentence, a.idAnnotationStatus,  ti.entry 
from annotationset a join typeinstance ti on (a.idAnnotationStatus = ti.idTypeInstance);

--
-- Construction
--
create or replace view view_construction as
select construction.idConstruction, construction.entry, construction.active, construction.idEntity
from Construction;

--
-- ConstructionElement
--
create or replace view view_constructionelement as
select construction.idConstruction, construction.entry as constructionEntry, construction.idEntity as constructionIdEntity, ce.idConstructionElement, ce.entry, ce.active, ce.idEntity, ce.idColor
from constructionelement ce join entityrelation er1 on (ce.idEntity = er1.idEntity1)
join relationtype rt1 on (er1.idRelationType = rt1.idRelationType)
join construction on (er1.idEntity2 = construction.idEntity)
where (rt1.entry = 'rel_elementof');


--
-- LabelFECETarget
--
create or replace view view_labelfecetarget as
SELECT AnnotationSet.idSubCorpus, 
    AnnotationSet.idSentence,
    LayerType.entry as layerTypeEntry,
    fe.idFrameElement, 
    ce.idConstructionElement, 
    gl.idGenericLabel,
    ifnull(Label.startChar,-1) AS startChar,
    ifnull(Label.endChar,-1) AS endChar,
    ifnull(color_ce.rgbFg, ifnull(color_fe.rgbFg, color_gl.rgbFg)) AS rgbFg,
    ifnull(color_ce.rgbBg, ifnull(color_fe.rgbBg, color_gl.rgbBg)) AS rgbBg,
    entry_it.idLanguage, 
    entry_it.name AS instantiationType
FROM AnnotationSet
    INNER JOIN Layer
        ON (AnnotationSet.idAnnotationSet = Layer.idAnnotationSet)
    INNER JOIN LayerType
        ON (Layer.idLayerType = LayerType.idLayerType)
    INNER JOIN Label
        ON (Layer.idLayer = Label.idLayer)
    INNER JOIN TypeInstance
        ON (Label.idInstantiationType = TypeInstance.idTypeInstance)
    INNER JOIN Entry entry_it
        ON (TypeInstance.entry = entry_it.entry)
    LEFT JOIN FrameElement fe
        ON (Label.idLabelType=fe.idEntity)
    LEFT JOIN Color color_fe
        ON (fe.idColor = color_fe.idColor)
    LEFT JOIN ConstructionElement ce
        ON (Label.idLabelType=ce.idEntity)
    LEFT JOIN Color color_ce
        ON (ce.idColor = color_ce.idColor)
    LEFT JOIN GenericLabel gl
        ON (Label.idLabelType=gl.idEntity)
    LEFT JOIN Color color_gl
        ON (gl.idColor = color_gl.idColor)
    WHERE ((LayerType.entry = 'lty_fe') or (LayerType.entry = 'lty_ce') or (LayerType.entry = 'lty_target'))
    ORDER BY AnnotationSet.idSentence,Label.startChar

--
-- Relations
--

create or replace view view_relation as
select er.idEntityRelation, d.entry as domain, rg.entry as relationGroup, rt.idRelationType, rt.entry as relationType, er.idEntity1, e1.type as entity1Type, er.idEntity2, e2.type as entity2Type, er.idEntity3, e3.type as entity3Type
from entityrelation er join relationtype rt on (er.idRelationType = rt.idRelationType)
join relationgroup rg on (rt.idRelationGroup = rg.idRelationGroup)
join domain d on (rt.idDomain = d.idDomain)
join entity e1 on (er.idEntity1 = e1.idEntity)
join entity e2 on (er.idEntity2 = e2.idEntity)
left join entity e3 on (er.idEntity3 = e3.idEntity);

--
-- Layer
--
create or replace view view_layer as
select l.idLayer, l.rank, l.idAnnotationSet, l.idLayerType, lt.entry, lt.idEntity
from Layer l inner join LayerType lt on (l.idLayerType = lt.idLayerType);

--
-- Label
--

select a.idAnnotationSet, a.idSentence, a.idSubCorpus, l.idLayer, l.entry, lb.idLabel, it.entry, fe.entry, ce.entry, gl.name
from View_AnnotationSet a
	inner join View_Layer l on (a.idAnnotationSet = l.idAnnotationSet)
	inner join Label lb on (l.idLayer = lb.idLayer)
	inner join View_InstantiationType it on (lb.idInstantiationType = it.idTypeInstance)
	left  join View_FrameElement fe on (lb.idLabelType = fe.idEntity)
	left  join View_ConstructionElement ce on (lb.idLabelType = ce.idEntity)
	left  join GenericLabel gl on (lb.idLabelType = gl.IdEntity)

--
-- Constraints
--

create or replace view view_constraint as
select r.idEntityRelation, r.relationType as entry, 
    r.idEntity1 as idConstraint, r.entity1Type as constraintType, 
    r.idEntity2 as idConstrained, r.entity2Type as constrainedType
    r.idEntity3 as idConstrainedBy, r.entity3Type as constrainedByType
from view_relation r
where r.relationGroup = 'rgp_constraints';

--
-- SemanticType
--
create or replace view view_semantictype as
select st.idSemanticType, st.entry, st.idEntity, st.idDomain, d.entry as domainEntry
from SemanticType st join Domain d on (st.idDomain = d.idDomain);

