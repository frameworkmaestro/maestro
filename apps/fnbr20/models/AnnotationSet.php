<?php

/**
 *
 *
 * @category   Maestro
 * @package    UFJF
 * @subpackage fnbr20
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version
 * @since
 */

namespace fnbr20\models;

class AnnotationSet extends map\AnnotationSetMap
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(
                'timeline' => array('notnull'),
                'idSubCorpus' => array('notnull'),
                'idSentence' => array('notnull'),
                'idAnnotationStatus' => array('notnull'),
            ),
            'converters' => array()
        );
    }

    public function getDescription()
    {
        return $this->getIdAnnotationSet();
    }

    public function setIdAnnotationStatus($value)
    {
        if (substr($value, 0, 3) == 'ast') {
            $as = new TypeInstance();
            $filter = (object)['entry' => $value];
            $idStatus = $as->listAnnotationStatus($filter)->asQuery()->getResult()[0]['idAnnotationStatus'];
        } else {
            $idStatus = $value;
        }
        parent::setIdAnnotationStatus($idStatus);
    }

    public function getFullAnnotationStatus()
    {
        $idAnnotationStatus = ($this->getIdAnnotationStatus() ?: '0');
        $criteria = $this->getCriteria()->
        select('idAnnotationStatus, annotationStatus.entry, annotationStatus.entries.name as annotationStatus, annotationStatus.idTypeInstance,' .
            'annotationStatus.color.rgbBg');
        if ($idAnnotationStatus) {
            $criteria->where("idAnnotationStatus = {$idAnnotationStatus}");
        }
        Base::entryLanguage($criteria, 'annotationStatus');
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idAnnotationSet');
        if ($filter->idAnnotationSet) {
            $criteria->where("idAnnotationSet LIKE '{$filter->idAnnotationSet}%'");
        }
        return $criteria;
    }

    public function getIdLU()
    {
        return $this->getSubCorpus()->getIdLU();
    }

    public function getWords($idSentence)
    {
        $criteria = $this->getCriteria()->
        select('sentence.text')->
        where("idSentence = {$idSentence}");
        $result = $criteria->asQuery()->getResult();
        $text = utf8_decode($result[0][0]);
        $array = array();
        $punctuation = " .,;:?/'][\{\}\"!@#$%&*\(\)-_+=";
        $word = "";
        $i = 0;
        for ($j = 0; $j < strlen($text); $j++) {
            $char = $text{$j};
            $break = (strpos($punctuation, $char) !== false);
            if ($break) {
                $word = substr($text, $i, $j - $i);
                $array[$i] = $word;
                $array[$j] = $char;
                $i = $j + 1;
            }
        }
        $values[-1] = [0, '', -1];
        $order = 0;
        foreach ($array as $startChar => $wordForm) {
            $endChar = $startChar + strlen($wordForm) - 1;
            $lWordForm = utf8_encode(str_replace("'", "\\'", $wordForm));
            ++$order;
            $values[$startChar] = [$order, $lWordForm, $startChar, $endChar]; //"{$order}, {$startChar}, {$endChar}, {$idSentence}, 0, '{$lWordForm}'";
        }
        return $values;
    }

    public function getWordsChars($idSentence)
    {
        $criteria = $this->getCriteria()->
        select('sentence.text')->
        where("idSentence = {$idSentence}");
        $result = $criteria->asQuery()->getResult();
        $text = utf8_decode($result[0]['text']);
        $array = array();
        $punctuation = " .,;:?/'][\{\}\"!@#$%&*\(\)-_+=";
        $word = "";
        $i = 0;
        for ($j = 0; $j < strlen($text); $j++) {
            $char = $text{$j};
            $break = (strpos($punctuation, $char) !== false);
            if ($break) {
                $word = substr($text, $i, $j - $i);
                $array[$i] = $word;
                $array[$j] = $char;
                $i = $j + 1;
            }
        }
        $chars = [];
        $order = 1;
        foreach ($array as $startChar => $wordForm) {
            $endChar = $startChar + strlen($wordForm) - 1;
            $lWordForm = utf8_encode(str_replace("'", "\\'", $wordForm));
            $words[(string)$order] = [
                'order' => $order,
                'word' => $lWordForm,
                'startChar' => $startChar,
                'endChar' => $endChar
            ];
            for ($pos = (int)$startChar; $pos <= $endChar; $pos++) {
                $o = $pos - $startChar;
                $chars[$pos] = [
                    'offset' => (string)$o,
                    'char' => str_replace("'", "\\'", utf8_encode($wordForm{$o})),
                    'order' => $order
                ];
            }
            ++$order;
        }
        $wordsChars = new \StdClass();
        $wordsChars->words = $words;
        $wordsChars->chars = $chars;
        return $wordsChars;
    }

    public function getAnnotationSets($idSentence)
    {
        $as = new ViewAnnotationSet();
        $criteriaLU = $as->getCriteria()
            ->select("idAnnotationSet, concat(frameEntries.name, '.', view_lu.name) as name")
            ->where("idSentence = {$idSentence}");
        $criteriaLU->setDistinct(true);
        $criteriaLU->associationAlias("subcorpuslu.lu.frame.entries", "frameEntries");
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteriaLU->where("idAnnotationSet = {$this->getId()}");
        }
        Base::entryLanguage($criteriaLU,"frameEntries.");

        $criteriaCxn = $as->getCriteria()
            ->select("idAnnotationSet, cxnEntries.name as name")
            ->where("idSentence = {$idSentence}");
        $criteriaCxn->setDistinct(true);
        $criteriaCxn->associationAlias("subcorpuscxn.construction.entries", "cxnEntries");
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteriaCxn->where("idAnnotationSet = {$this->getId()}");
        }
        Base::entryLanguage($criteriaCxn,"cxnEntries.");

        $criteria = $criteriaLU;
        $criteria->union($criteriaCxn);
        $result = $criteria->asQuery()->getResult();

        return $result;
    }

    public function getLayers($idSentence)
    {
        $criteria = $this->getCriteria();
        $criteria->select('layers.idLayer, layers.layertype.entries.name as name, idAnnotationSet');
        $criteria->where("idSentence = {$idSentence}");
        Base::entryLanguage($criteria, 'layers.layertype');
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteria->where("idAnnotationSet = {$this->getId()}");
        }
        return $criteria->asQuery()->getResult();
    }

    public function getLabels($idSentence)
    {
        $criteria = $this->getCriteria();
        $criteria->select('layers.idLayer, layers.labels.idLabel, layers.labels.idLabelType');
        $criteria->where("idSentence = {$idSentence}");
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteria->where("idAnnotationSet = {$this->getId()}");
        }
        return $criteria->asQuery()->getResult();
    }

    public function getLabelTypesGLGF($idSentence)
    {
        $criteria = $this->getCriteria();
        $criteria->select("idAnnotationSet, l.idLayer, genericlabel.idEntity as idLabelType, genericlabel.name as labelType, genericlabel.idColor, '' as coreType");
        Base::relation($criteria, 'lu', 'subcorpus sc', 'rel_hassubcorpus');
        $criteria->join('subcorpus sc', 'annotationset', "sc.idSubCorpus = annotationset.idSubCorpus");
        $criteria->join('annotationset', 'sentence s', "annotationset.idSentence = s.idSentence");
        $criteria->join('annotationset', 'layer l', "annotationset.idAnnotationSet = l.idAnnotationSet");
        $criteria->join('layer l', 'layertype lt', "l.idLayerType=lt.idLayerType");
        Base::relation($criteria, 'layertype lt', 'genericlabel', 'rel_haslabeltype');
        Base::relation($criteria, 'genericlabel', 'pos p', 'rel_gfpos');
        $criteria->where("idSentence = {$idSentence}");
        $criteria->where("lu.lemma.pos.entry = p.entry");
        $criteria->where("lt.entry = 'lty_gf'");
        $criteria->where("(genericlabel.idLanguage = s.idLanguage)");
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteria->where("idAnnotationSet = {$this->getId()}");
        }
        $criteria->orderBy('idAnnotationSet, l.idLayer, genericlabel.name');
        return $criteria;
    }

    public function getLabelTypesGL($idSentence)
    {
        $criteria = $this->getCriteria();
        $criteria->select("idAnnotationSet, l.idLayer, genericlabel.idEntity as idLabelType, genericlabel.name as labelType, genericlabel.idColor, '' as coreType");
        $criteria->join('annotationset', 'sentence s', "annotationset.idSentence = s.idSentence");
        $criteria->join('annotationset', 'layer l', "annotationset.idAnnotationSet = l.idAnnotationSet");
        $criteria->join('layer l', 'layertype lt', "l.idLayerType=lt.idLayerType");
        Base::relation($criteria, 'layertype lt', 'genericlabel', 'rel_haslabeltype');
        $criteria->where("idSentence = {$idSentence}");
        $criteria->where("lt.entry <> 'lty_gf'");
        $criteria->where("(genericlabel.idLanguage = s.idLanguage)");
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $criteria->where("idAnnotationSet = {$this->getId()}");
        }
        $criteria->orderBy('idAnnotationSet, l.idLayer, genericlabel.name');
        return $criteria;
    }

    public function getLabelTypesFE($idSentence, $forceId = false)
    {
        if ((!\Manager::checkAccess('MASTER', A_EXECUTE)) || $forceId) {
            $condition = "AND (a.idAnnotationSet = {$this->getId()})";
        }

        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE

        SELECT a.idAnnotationSet,
            l.idLayer,
            fe.idEntity AS idLabelType,
            e.name AS labelType,
            fe.idColor,
            fe.typeEntry AS coreType
        FROM View_AnnotationSet a
            INNER JOIN View_Layer l on (a.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN View_SubCorpusLU sc on (a.idSubCorpus = sc.idSubCorpus)
            INNER JOIN View_LU lu on (sc.idLU = lu.idLU)
            INNER JOIN View_FrameElement fe on (lu.idFrame = fe.idFrame)
            INNER JOIN View_EntryLanguage e on (fe.entry = e.entry)
        WHERE (e.idLanguage = {$idLanguage} )
            AND (l.entry = 'lty_fe' )
            AND (a.idSentence = {$idSentence})
            {$condition}
        ORDER BY a.idAnnotationSet, l.idLayer, fe.typeEntry, e.name
HERE;

        /*
        $cmd = <<<HERE

        SELECT AnnotationSet.idAnnotationSet,
            l.idLayer,
            FrameElement.idEntity AS idLabelType,
            Entry.name            AS labelType,
            FrameElement.idColor,
            TypeInstance.entry AS coreType
        FROM AnnotationSet
            INNER JOIN Layer l
                ON (AnnotationSet.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN LayerType lt
                ON (l.idLayerType = lt.idLayerType)
HERE;
        // subcorpus-lu
        $cmd .= <<<HERE
            INNER JOIN SubCorpus
                ON (AnnotationSet.idSubCorpus = SubCorpus.idSubCorpus)
            INNER JOIN EntityRelation er_lu_subcorpus
                ON (SubCorpus.idEntity = er_lu_subcorpus.idEntity2)
            INNER JOIN RelationType rt_lu_subcorpus
                ON (er_lu_subcorpus.idRelationType = rt_lu_subcorpus.idRelationType)
            INNER JOIN LU
                ON (er_lu_subcorpus.idEntity1 = LU.idEntity)
HERE;
        // lu-frame
        $cmd .= <<<HERE
            INNER JOIN EntityRelation er_lu_frame
                ON (LU.idEntity = er_lu_frame.idEntity1)
            INNER JOIN RelationType rt_lu_frame
                ON (er_lu_frame.idRelationType = rt_lu_frame.idRelationType)
            INNER JOIN Frame
                ON (er_lu_frame.idEntity2 = Frame.idEntity)
HERE;
        // frame-frameelement
        $cmd .= <<<HERE
            INNER JOIN EntityRelation er_frame_frameelement
                ON (Frame.idEntity = er_frame_frameelement.idEntity2)
            INNER JOIN RelationType rt_frame_frameelement
                ON (er_frame_frameelement.idRelationType = rt_frame_frameelement.idRelationType)
            INNER JOIN FrameElement
                ON (er_frame_frameelement.idEntity1 = FrameElement.idEntity)
HERE;
        // frameelement-typeinstance
        $cmd .= <<<HERE
            INNER JOIN EntityRelation er_frameelement_ti
                ON (FrameElement.idEntity = er_frameelement_ti.idEntity1)
            INNER JOIN RelationType rt_frameelement_ti
                ON (er_frameelement_ti.idRelationType = rt_frameelement_ti.idRelationType)
            INNER JOIN TypeInstance
                ON (er_frameelement_ti.idEntity2 = TypeInstance.idEntity)
            INNER JOIN Entry
                ON (FrameElement.entry=Entry.entry)
HERE;
        $cmd .= <<<HERE
        WHERE ((rt_lu_subcorpus.entry = 'rel_hassubcorpus' )
            AND (rt_lu_frame.entry = 'rel_evokes' )
            AND (rt_frame_frameelement.entry = 'rel_elementof' )
            AND (Entry.idLanguage = {$idLanguage} )
            AND (rt_frameelement_ti.entry = 'rel_hastype' )
            AND (lt.entry = 'lty_fe' )
            {$condition} AND (AnnotationSet.idSentence = {$idSentence}))
        ORDER BY AnnotationSet.idAnnotationSet, l.idLayer, TypeInstance.entry, Entry.name 
            
HERE;
        */
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getLabelTypesCE($idSentence)
    {
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $condition = "AND (a.idAnnotationSet = {$this->getId()})";
        }

        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE

        SELECT a.idAnnotationSet,
            l.idLayer,
            ce.idEntity AS idLabelType,
            e.name AS labelType,
            ce.idColor,
            '' AS coreType
        FROM View_AnnotationSet a
            INNER JOIN View_Layer l on (a.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN View_SubCorpusCxn sc on (a.idSubCorpus = sc.idSubCorpus)
            INNER JOIN View_ConstructionElement ce on (sc.idConstruction = ce.idConstruction)
            INNER JOIN View_EntryLanguage e on (ce.entry = e.entry)
        WHERE (e.idLanguage = {$idLanguage} )
            AND (l.entry = 'lty_ce' )
            AND (a.idSentence = {$idSentence})
            {$condition}
        ORDER BY a.idAnnotationSet, l.idLayer, e.name
HERE;



        /*
        $cmd = <<<HERE

        SELECT AnnotationSet.idAnnotationSet,
            l.idLayer,
            ConstructionElement.idEntity AS idLabelType,
            Entry.name AS labelType,
            ConstructionElement.idColor,
            '' AS coreType
        FROM AnnotationSet
            INNER JOIN Layer l
                ON (AnnotationSet.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN LayerType lt
                ON (l.idLayerType = lt.idLayerType)
HERE;
        // subcorpus-cxn
        $cmd .= <<<HERE
            INNER JOIN SubCorpus
                ON (AnnotationSet.idSubCorpus = SubCorpus.idSubCorpus)
            INNER JOIN EntityRelation er_cxn_subcorpus
                ON (SubCorpus.idEntity = er_cxn_subcorpus.idEntity2)
            INNER JOIN RelationType rt_cxn_subcorpus
                ON (er_cxn_subcorpus.idRelationType = rt_cxn_subcorpus.idRelationType)
            INNER JOIN Construction
                ON (er_cxn_subcorpus.idEntity1 = Construction.idEntity)
HERE;
        // cxn-constructionelement
        $cmd .= <<<HERE
            INNER JOIN EntityRelation er_cxn_constructionelement
                ON (Construction.idEntity = er_cxn_constructionelement.idEntity2)
            INNER JOIN RelationType rt_cxn_constructionelement
                ON (er_cxn_constructionelement.idRelationType = rt_cxn_constructionelement.idRelationType)
            INNER JOIN ConstructionElement
                ON (er_cxn_constructionelement.idEntity1 = ConstructionElement.idEntity)
            INNER JOIN Entry
                ON (ConstructionElement.entry=Entry.entry)
HERE;
        $cmd .= <<<HERE
        WHERE ((rt_cxn_subcorpus.entry = 'rel_hassubcorpus' )
            AND (rt_cxn_constructionelement.entry = 'rel_elementof' )
            AND (Entry.idLanguage = {$idLanguage} )
            AND (lt.entry = 'lty_ce' )
            {$condition} AND (AnnotationSet.idSentence = {$idSentence}))
        ORDER BY AnnotationSet.idAnnotationSet, l.idLayer, Entry.name 
            
HERE;
        */
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getLayerNameCnxFrame($idSentence)
    {
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $condition = "AND (a.idAnnotationSet = {$this->getId()})";
        }

        $idLanguage = \Manager::getSession()->idLanguage;

        $transaction = $this->getDb()->beginTransaction();

        //$cmd = "SET @rowNumberX = 0;SET @rowNumberY = 0;";
        //$this->getDb()->executeCommand($cmd);

        $cmd = <<<HERE
            SELECT concat('lty_cefe_', f.idFrame) as idLayer, f.idFrame, e.name
            FROM View_AnnotationSet a
                INNER JOIN View_SubCorpusCxn sc on (a.idSubCorpus = sc.idSubCorpus)
                INNER JOIN View_Construction c on (sc.idConstruction = c.idConstruction)
                INNER JOIN View_Relation r on (c.idEntity = r.idEntity1)
                INNER JOIN View_Frame f on (r.idEntity2 = f.idEntity)
                INNER JOIN View_EntryLanguage e on (f.entry = e.entry)
            WHERE (e.idLanguage = {$idLanguage})
                AND (r.relationType = 'rel_evokes' )
                {$condition}
                AND (a.idSentence = {$idSentence})
HERE;

        /*
        /*
        $cmd = <<<HERE
        SELECT
            X.idLayer, Y.idFrame, Y.name
        FROM
            (SELECT (@rowNumberX := @rowNumberX + 1) AS num,
                a.idAnnotationSet,
                l.idLayer
            FROM View_AnnotationSet a
                INNER JOIN View_Layer l
                    ON (a.idAnnotationSet = l.idAnnotationSet)
            WHERE
                (a.idSentence = {$idSentence})
                {$condition}
                AND (l.entry = 'lty_cefe' )
            ) X,
            ( SELECT (@rowNumberY := @rowNumberY + 1) AS num,
                f.idFrame, e.name
            FROM View_AnnotationSet a
                INNER JOIN View_Layer l on (a.idAnnotationSet = l.idAnnotationSet)
                INNER JOIN View_SubCorpusCxn sc on (a.idSubCorpus = sc.idSubCorpus)
                INNER JOIN View_Construction c on (sc.idConstruction = c.idConstruction)
                INNER JOIN View_Relation r on (c.idEntity = r.idEntity1)
                INNER JOIN View_Frame f on (r.idEntity2 = f.idEntity)
                INNER JOIN View_EntryLanguage e on (f.entry = e.entry)
            WHERE (e.idLanguage = {$idLanguage})
                AND (r.relationType = 'rel_evokes' )
                {$condition}
                AND (a.idSentence = {$idSentence})
            ) Y
            WHERE (X.num = Y.num)
HERE;
        */

/*
        $cmd = <<<HERE
        SELECT 
            A.idLayer, B.idFrame, B.name
        FROM
            (SELECT (@rowNumberA := @rowNumberA + 1) AS num,
                AnnotationSet.idAnnotationSet,
                l.idLayer
            FROM AnnotationSet
                INNER JOIN Layer l
                    ON (AnnotationSet.idAnnotationSet = l.idAnnotationSet)
                INNER JOIN LayerType lt
                    ON (l.idLayerType = lt.idLayerType)
            WHERE
                (AnnotationSet.idSentence = {$idSentence})
                {$condition}
                AND (lt.entry = 'lty_cefe' )
            ) A,
            ( SELECT (@rowNumberB := @rowNumberB + 1) AS num,
                Frame.idFrame, Entry.name
            FROM AnnotationSet
                INNER JOIN SubCorpus
                    ON (AnnotationSet.idSubCorpus = SubCorpus.idSubCorpus)
                INNER JOIN EntityRelation er_cxn_subcorpus
                    ON (SubCorpus.idEntity = er_cxn_subcorpus.idEntity2)
                INNER JOIN RelationType rt_cxn_subcorpus
                    ON (er_cxn_subcorpus.idRelationType = rt_cxn_subcorpus.idRelationType)
                INNER JOIN Construction
                    ON (er_cxn_subcorpus.idEntity1 = Construction.idEntity)
                INNER JOIN EntityRelation er_cxn_frame
                    ON (Construction.idEntity = er_cxn_frame.idEntity1)
                INNER JOIN RelationType rt_cxn_frame
                    ON (er_cxn_frame.idRelationType = rt_cxn_frame.idRelationType)
                INNER JOIN Frame
                    ON (er_cxn_frame.idEntity2 = Frame.idEntity)
                INNER JOIN Entry
                    ON (Frame.entry=Entry.entry)
             WHERE ((rt_cxn_subcorpus.entry = 'rel_hassubcorpus' )
                AND (rt_cxn_frame.entry = 'rel_evokes' )
                AND (Entry.idLanguage = {$idLanguage} )
               {$condition} AND (AnnotationSet.idSentence = {$idSentence}))
            ) B
            WHERE (A.num = B.num)

HERE;
        */
        $transaction->commit();
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getElementCxnFrame($idFrame)
    {
        //$idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT
            ce.idEntity idEntityCE, fe.idEntity idEntityFE
        FROM
            View_ConstructionElement ce
                INNER JOIN View_Relation r on (ce.idEntity = r.idEntity1)
                INNER JOIN View_FrameElement fe on (r.idEntity2 = fe.idEntity)
            WHERE (r.relationType = 'rel_evokes' )
                AND (fe.idFrame = {$idFrame})
HERE;

        /*
        $cmd = <<<HERE
        SELECT 
            ConstructionElement.idEntity idEntityCE, FrameElement.idEntity idEntityFE
        FROM
            ConstructionElement
            INNER JOIN EntityRelation er_cxnelement_frameelement
                ON (ConstructionElement.idEntity = er_cxnelement_frameelement.idEntity1)
            INNER JOIN RelationType rt_cxnelement_frameelement
                ON (er_cxnelement_frameelement.idRelationType = rt_cxnelement_frameelement.idRelationType)
            INNER JOIN FrameElement
                ON (er_cxnelement_frameelement.idEntity2 = FrameElement.idEntity)
            INNER JOIN EntityRelation er_frameelement_frame
                ON (FrameElement.idEntity = er_frameelement_frame.idEntity1)
            INNER JOIN RelationType rt_frameelement_frame
                ON (er_frameelement_frame.idRelationType = rt_frameelement_frame.idRelationType)
            INNER JOIN Frame
                ON (er_frameelement_frame.idEntity2 = Frame.idEntity)
            WHERE ((rt_cxnelement_frameelement.entry = 'rel_evokes' )
                AND (rt_frameelement_frame.entry = 'rel_elementof' )
                AND (Frame.idFrame = {$idFrame}))
HERE;

        */

        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getLabelTypesCEFE($idSentence)
    {
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $condition = "AND (a.idAnnotationSet = {$this->getId()})";
        }

        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT a.idAnnotationSet,
            concat('lty_cefe_', fe.idFrame) as idLayer,
            fe.idEntity AS idLabelType,
            e.name AS labelType,
            fe.idColor,
            '' AS coreType
        FROM View_AnnotationSet a
            INNER JOIN View_SubCorpusCxn sc on (a.idSubCorpus = sc.idSubCorpus)
            INNER JOIN View_ConstructionElement ce on (sc.idConstruction = ce.idConstruction)
            INNER JOIN View_Relation r on (ce.idEntity = r.idEntity1)
            INNER JOIN View_FrameElement fe on (r.idEntity2 = fe.idEntity)
            INNER JOIN View_EntryLanguage e on (fe.entry = e.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (r.relationType = 'rel_evokes')
            {$condition}
            AND (a.idSentence = {$idSentence})
        ORDER BY a.idAnnotationSet, idLayer, e.name

HERE;

        /*
        $cmd = <<<HERE
        SELECT a.idAnnotationSet,
            l.idLayer,
            fe.idEntity AS idLabelType,
            e.name AS labelType,
            fe.idColor,
            '' AS coreType
        FROM View_AnnotationSet a
            INNER JOIN View_Layer l ON (a.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN View_SubCorpusCxn sc on (a.idSubCorpus = sc.idSubCorpus)
            INNER JOIN View_ConstructionElement ce on (sc.idConstruction = ce.idConstruction)
            INNER JOIN View_Relation r on (ce.idEntity = r.idEntity1)
            INNER JOIN View_FrameElement fe on (r.idEntity2 = fe.idEntity)
            INNER JOIN View_EntryLanguage e on (fe.entry = e.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (r.relationType = 'rel_evokes')
            AND (l.entry = 'lty_cefe')
            {$condition}
            AND (a.idSentence = {$idSentence})
        ORDER BY a.idAnnotationSet, l.idLayer, e.name

HERE;
        */

        /*
        $cmd = <<<HERE
          SELECT AnnotationSet.idAnnotationSet,
          l.idLayer,
          FrameElement.idEntity AS idLabelType,
          Entry.name AS labelType,
          FrameElement.idColor,
          '' AS coreType
          FROM AnnotationSet
          INNER JOIN Layer l
          ON (AnnotationSet.idAnnotationSet = l.idAnnotationSet)
          INNER JOIN LayerType lt
          ON (l.idLayerType = lt.idLayerType)
HERE;
        // subcorpus-cxn
        $cmd .= <<<HERE
          INNER JOIN SubCorpus
          ON (AnnotationSet.idSubCorpus = SubCorpus.idSubCorpus)
          INNER JOIN EntityRelation er_cxn_subcorpus
          ON (SubCorpus.idEntity = er_cxn_subcorpus.idEntity2)
          INNER JOIN RelationType rt_cxn_subcorpus
          ON (er_cxn_subcorpus.idRelationType = rt_cxn_subcorpus.idRelationType)
          INNER JOIN Construction
          ON (er_cxn_subcorpus.idEntity1 = Construction.idEntity)
HERE;
        // cxn-constructionelement
        $cmd .= <<<HERE
          INNER JOIN EntityRelation er_cxn_constructionelement
          ON (Construction.idEntity = er_cxn_constructionelement.idEntity2)
          INNER JOIN RelationType rt_cxn_constructionelement
          ON (er_cxn_constructionelement.idRelationType = rt_cxn_constructionelement.idRelationType)
          INNER JOIN ConstructionElement
          ON (er_cxn_constructionelement.idEntity1 = ConstructionElement.idEntity)
HERE;
        // constructionelement-frameelement (ce-fe)
        $cmd .= <<<HERE
          INNER JOIN EntityRelation er_cxnelement_frameelement
          ON (ConstructionElement.idEntity = er_cxnelement_frameelement.idEntity1)
          INNER JOIN RelationType rt_cxnelement_frameelement
          ON (er_cxnelement_frameelement.idRelationType = rt_cxnelement_frameelement.idRelationType)
          INNER JOIN FrameElement
          ON (er_cxnelement_frameelement.idEntity2 = FrameElement.idEntity)
          INNER JOIN Entry
          ON (FrameElement.entry=Entry.entry)
HERE;
        $cmd .= <<<HERE
          WHERE ((rt_cxn_subcorpus.entry = 'rel_hassubcorpus' )
          AND (rt_cxn_constructionelement.entry = 'rel_elementof' )
          AND (rt_cxnelement_frameelement.entry = 'rel_evokes' )
          AND (Entry.idLanguage = {$idLanguage} )
          AND (lt.entry = 'lty_cefe' )
          {$condition} AND (AnnotationSet.idSentence = {$idSentence}))
          ORDER BY AnnotationSet.idAnnotationSet, l.idLayer, Entry.name

HERE;
        */
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getNI($idSentence, $idLanguage)
    {
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $condition = "AND (a.idAnnotationSet = {$this->getId()})";
        }

        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE

        SELECT lb.idLabel, l.idLayer, lb.idInstantiationType, eit.name as instantiationType, entry_fe.name as feName, fe.idColor as idColor, lb.idLabelType
        FROM Label lb
            INNER JOIN View_Layer l ON (lb.idLayer = l.idLayer)
            INNER JOIN View_AnnotationSet a ON (l.idAnnotationSet = a.idAnnotationSet)
            INNER JOIN View_InstantiationType it ON (lb.idInstantiationType = it.idTypeInstance)
            INNER JOIN View_EntryLanguage eit on (it.entry = eit.entry)
            INNER JOIN View_FrameElement fe
                ON (lb.idLabelType = fe.idEntity)
            INNER JOIN Entry entry_fe
                ON (fe.entry = entry_fe.entry)
        WHERE (it.entry <> 'int_normal')
            AND ((entry_fe.idLanguage = {$idLanguage}) or (entry_fe.idLanguage is null))
            {$condition}
            AND (a.idSentence = {$idSentence})

HERE;
        /*
        $cmd = <<<HERE

        SELECT idLabel, Layer.idLayer, idInstantiationType, entry_it.name as instantiationType, entry_fe.name as feName, FrameElement.idColor as idColor, idLabelType
        FROM Label
            INNER JOIN Layer
                ON (Label.idLayer = Layer.idLayer)
            INNER JOIN AnnotationSet
                ON (Layer.idAnnotationSet = AnnotationSet.idAnnotationSet)
            INNER JOIN TypeInstance
                ON (Label.idInstantiationType = TypeInstance.idTypeInstance)
            INNER JOIN Entry entry_it
                ON (TypeInstance.entry = entry_it.entry)
            INNER JOIN FrameElement
                ON (Label.idLabelType = FrameElement.idEntity)
            INNER JOIN Entry entry_fe
                ON (FrameElement.entry = entry_fe.entry)
        WHERE (entry_it.name <> 'Normal')
            AND (entry_it.idLanguage = {$idLanguage} )
            AND ((entry_fe.idLanguage = {$idLanguage}) or (entry_fe.idLanguage is null))
            {$condition} AND (AnnotationSet.idSentence = {$idSentence})
    
HERE;
        */
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getCEFEData($idSentence, $idCEFELayer)
    {
        $cmd = <<<HERE
        SELECT ifnull(lb.startChar,-1) AS startChar,
            ifnull(lb.endChar,-1) AS endChar,
            fe.idEntity AS idLabelType
        FROM View_AnnotationSet a
            INNER JOIN View_Layer l  ON (a.idAnnotationSet = l.idAnnotationSet)
            LEFT JOIN Label lb ON (l.idLayer=lb.idLayer)
            LEFT JOIN View_ConstructionElement ce on (lb.idLabelType = ce.idEntity)
            LEFT JOIN View_Relation r on (ce.idEntity = r.idEntity1)
            LEFT JOIN View_FrameElement fe on (r.idEntity2 = fe.idEntity)
        WHERE (r.relationType = 'rel_evokes')
            AND (a.idSentence = {$idSentence})
            AND (concat('lty_cefe_', fe.idFrame) = '{$idCEFELayer}')

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function getLayersData($idSentence)
    {
        if (!\Manager::checkAccess('MASTER', A_EXECUTE)) {
            $condition = "AND (AnnotationSet.idAnnotationSet = {$this->getId()})";
        }
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT a.idAnnotationSet,
            l.idLayerType,
            l.idLayer,
            el.name AS layer,
            ifnull(lb.startChar,-1) AS startChar,
            ifnull(lb.endChar,-1) AS endChar,
            ifnull(gl.idEntity, ifnull(fe.idEntity, ce.idEntity)) AS idLabelType,
            lb.idLabel,
            l.entry as layerTypeEntry
        FROM View_AnnotationSet a
            INNER JOIN View_Layer l ON (a.idAnnotationSet = l.idAnnotationSet)
            INNER JOIN View_EntryLanguage el on (l.entry = el.entry)
            LEFT JOIN Label lb ON (l.idLayer=lb.idLayer)
            LEFT JOIN GenericLabel gl ON (lb.idLabelType=gl.idEntity)
            LEFT JOIN View_FrameElement fe ON (lb.idLabelType=fe.idEntity)
            LEFT JOIN View_ConstructionElement ce ON (lb.idLabelType=ce.idEntity)
        WHERE (el.idLanguage = {$idLanguage} )
            {$condition}
            AND (a.idSentence = {$idSentence} )
        ORDER BY a.idAnnotationSet, l.layerOrder, l.idLayer, ifnull(lb.startChar,-1)

HERE;


        /*
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT AnnotationSet.idAnnotationSet,
            l.idLayerType,
            l.idLayer,
            entry_lt.name AS layer,
            ifnull(lb.startChar,-1) AS startChar,
            ifnull(lb.endChar,-1) AS endChar,
            ifnull(gl.idEntity, ifnull(fe.idEntity, ce.idEntity)) AS idLabelType,
            lb.idLabel,
            lt.entry as layerTypeEntry
        FROM AnnotationSet
            INNER JOIN Layer l
                ON (AnnotationSet.idAnnotationSet = l.idAnnotationSet)
            LEFT JOIN Label lb
                ON (l.idLayer=lb.idLayer)
            LEFT JOIN GenericLabel gl
                ON (lb.idLabelType=gl.idEntity)
            LEFT JOIN FrameElement fe
                ON (lb.idLabelType=fe.idEntity)
            LEFT JOIN Entry entry_fe
                ON (fe.entry = entry_fe.entry)
            LEFT JOIN ConstructionElement ce
                ON (lb.idLabelType=ce.idEntity)
            LEFT JOIN Entry entry_ce
                ON (ce.entry = entry_ce.entry)
            INNER JOIN LayerType lt
                ON (l.idLayerType=lt.idLayerType)
            INNER JOIN Entry entry_lt
                ON (lt.entry = entry_lt.entry)
        WHERE ((entry_lt.idLanguage = {$idLanguage} )
            AND ((gl.idLanguage = {$idLanguage}) or (gl.idLanguage is null))
            AND ((entry_fe.idLanguage = {$idLanguage}) or (entry_fe.idLanguage is null))
            {$condition} AND (AnnotationSet.idSentence       = {$idSentence} ))
        ORDER BY AnnotationSet.idAnnotationSet, lt.order, entry_lt.name, l.idLayer, ifnull(lb.startChar,-1)
    
HERE;
*/
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function addFELayer()
    {
        $layerType = new LayerType();
        $layerType->getByEntry('lty_fe');
        $layer = new Layer();
        $layer->setIdLayerType($layerType->getIdLayerType());
        $layer->setIdAnnotationSet($this->getId());
        $layer->setRank(0);
        $layer->save();
    }

    public function delFELayer()
    {
        $criteria = $this->getCriteria();
        $criteria->select('layers.idLayer');
        $criteria->where("idAnnotationSet = {$this->getId()}");
        $criteria->where("layers.layertype.entry = 'lty_fe'");
        $query = $criteria->asQuery();
        $rows = $query->getResult();
        $maxIdLayer = 0;
        foreach ($rows as $row) {
            if ($row['idLayer'] > $maxIdLayer) {
                $maxIdLayer = $row['idLayer'];
            }
        }
        if ($maxIdLayer > 0) {
            $layer = new Layer($maxIdLayer);
            $layer->delete();
        }
    }

    public function putLayers($layers)
    {
        //mdump($layers);
        $layerCE = new \StdClass();
        $type = new Type();
        $instances = $type->getInstantiationType('int_normal')->asQuery()->getResult();
        $itNormal = $instances[0]['idInstantiationType'];
        $hasFE = [];
        try {
            $transaction = $this->beginTransaction();
            $label = new Label();
            foreach ($layers as $layer) {
                $idLayer = $layer->idLayer;
                if ($idLayer == '') {
                    continue;
                }
                if ($layer->layerTypeEntry == 'lty_ce') {
                    $layerCE = $layer;
                }
                $labels = array();
                if ($layer->idLayerType != 0) {
                    $delCriteria = $label->getDeleteCriteria()->where("idLayer = {$idLayer}")->delete();
                    if ($layer->layerTypeEntry == 'lty_cefe') {
                        $idFrame = $layer->idFrame;
                        unset($layer);
                        $layer = clone $layerCE;
                        $layer->layerTypeEntry = 'lty_cefe';
                        $queryCEFE = $this->getElementCxnFrame($idFrame);
                        $cefe = $queryCEFE->chunkResult('idEntityCE', 'idEntityFE');
                        foreach ($layerCE as $key => $value) {
                            if (substr($key, 0, 2) == 'wf') {
                                if ($cefe[$value]) {
                                    $layer->$key = $cefe[$value];
                                }
                            }
                        }
                    }
                    $i = -1;
                    $l = 0;
                    $o = -1;
                    foreach ($layer as $key => $value) {
                        if (substr($key, 0, 2) == 'wf') {
                            $idLabelType = $layer->$key;
                            if ($idLabelType == '') {
                                continue;
                            }
                            $pos = (int)(substr($key, 2));
                            if (($idLabelType != $l) || ($pos > $o)) {
                                $i++;
                                $labels[$i] = (object)['idLabelType' => $idLabelType, 'startChar' => $pos, 'endChar' => $pos];
                                $l = $idLabelType;
                            } else {
                                $labels[$i]->endChar = $pos;
                            }
                            $o = $pos + 1;
                        }
                    }
                }
                if (count($labels)) {
                    if ($layer->layerTypeEntry == 'lty_fe') {
                        $hasFE[$layer->idAnnotationSet] = true;
                    }
                    if ($layer->layerTypeEntry == 'lty_ce') {
                        $hasFE[$layer->idAnnotationSet] = true;
                    }
                    foreach ($labels as $labelObj) {
                        $label->setPersistent(FALSE);
                        $label->setIdLayer($idLayer);
                        $label->setIdLabelType($labelObj->idLabelType);
                        $label->setStartChar($labelObj->startChar);
                        $label->setEndChar($labelObj->endChar);
                        $label->setMulti(0);
                        $label->setIdInstantiationType($itNormal);
                        $label->save();
                    }
                }
                if ($layer->ni->$idLayer) {
                    $hasFE[$layer->idAnnotationSet] = true;
                    foreach ($layer->ni->$idLayer as $idLabelType => $ni) {
                        $label->setPersistent(FALSE);
                        $label->setIdLayer($idLayer);
                        $label->setIdLabelType($idLabelType);
                        $label->setidInstantiationType($ni->idInstantiationType);
                        $label->setStartChar(NULL);
                        $label->setEndChar(NULL);
                        $label->setMulti(0);
                        $label->save();
                    }
                }
            }
            $transaction->commit();
            return $hasFE;
        } catch (EModelException $e) {
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
    }

    public function createLayersForLU($lu, $data)
    {
        $layerType = new LayerType();
        $layerTypes = $layerType->listToLU($lu);
        foreach ($layerTypes as $lt) {
            $layer = new Layer();
            $layer->setIdLayerType($lt['idLayerType']);
            $layer->setIdAnnotationSet($this->getId());
            $layer->setRank(1);
            $layer->save();
            if ($lt['entry'] == 'lty_target') {
                $label = new Label();
                $label->setMulti(0);
                $label->setIdInstantiationTypeFromEntry('int_normal');
                $idLabelType = $layerType->listLabelType((object)['entry' => 'lty_target'])->asQuery()->getResult()[0]['idLabelType'];
                $label->setIdLabelType($idLabelType);
                $label->setIdLayer($layer->getId());
                $label->setStartChar($data->startChar);
                $label->setEndChar($data->endChar);
                $label->save();
            }
        }
    }

    public function createLayersForCxn($cxn, $data)
    {
        $layerType = new LayerType();
        $layerTypes = $layerType->listToConstruction();
        foreach ($layerTypes as $lt) {
            $layer = new Layer();
            $layer->setIdLayerType($lt['idLayerType']);
            $layer->setIdAnnotationSet($this->getId());
            $layer->setRank(1);
            $layer->save();
        }
        // obtem as relações CXN-FR e cria as camadas FE
        // update 17/02/2016: such layers must be "dynamic",
        // because a new relation can be created after import
        // or a existent relation can be deleted
        /*
        $layerCEFE = $layerType->listCEFE();
        $er = new EntityRelation();
        $relations = $er->listCxnFrameRelations($cxn->getIdEntity())->asQuery()->getResult();
        foreach ($relations as $relation) {
            $layer = new Layer();
            $layer->setIdLayerType($layerCEFE[0]['idLayerType']);
            $layer->setIdAnnotationSet($this->getId());
            $layer->setRank(1);
            $layer->save();
        }
        */
    }

    public function deleteBySubCorpus($idSubCorpus)
    {
        $transaction = $this->beginTransaction();
        try {
            $this->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

}
